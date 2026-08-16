<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Despesa;
use App\Models\HistoricoDespesa;
use App\Models\Produto;
use App\Models\Renda;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class RelatorioController extends Controller
{
    /**
     * Helper privado para instanciar MPDF configurado
     */
    private function createMpdf(string $title): Mpdf
    {
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 12,
            'margin_right' => 12,
            'margin_top' => 15,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetTitle($title);

        return $mpdf;
    }

    /**
     * Helper privado para retornar a resposta HTTP do PDF
     */
    private function pdfResponse(string $pdfOutput, string $filename): Response
    {
        return response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    /**
     * 1. Relatório de Custo Mensal de Despesas
     */
    public function relatorioDespesasCustoMensal(Request $request): Response
    {
        $mes = (int) $request->input('mes', date('m'));
        $ano = (int) $request->input('ano', date('Y'));

        $query = Despesa::query()
            ->with(['tipoDespesa', 'statusDespesa', 'plano']);

        if (Auth::check()) {
            $query->whereHas('plano', fn ($q) => $q->where('user_id', Auth::id()));
        }

        // Se o mês/ano solicitado não possui despesas, realiza o fallback automático
        // para o mês/ano mais recente que contém registros no banco de dados.
        $countCurrent = (clone $query)
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->count();

        if ($countCurrent === 0) {
            $latest = (clone $query)->orderByDesc('data_vencimento')->first();
            if ($latest && $latest->data_vencimento) {
                $date = \Carbon\Carbon::parse($latest->data_vencimento);
                $mes = (int) $date->format('m');
                $ano = (int) $date->format('Y');
            }
        }

        $query->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano);

        $totalGasto = (float) (clone $query)->sum('valor_documento');
        $qtdDespesas = (int) (clone $query)->count();
        $custoMedio = $qtdDespesas > 0 ? ($totalGasto / $qtdDespesas) : 0.0;

        $despesasMaisCaras = (clone $query)
            ->orderByDesc('valor_documento')
            ->limit(5)
            ->get();

        $gastosPorCategoria = (clone $query)
            ->select(
                'tipo_despesa_id',
                DB::raw('SUM(valor_documento) as total_gasto'),
                DB::raw('COUNT(*) as total_itens')
            )
            ->groupBy('tipo_despesa_id')
            ->with('tipoDespesa')
            ->orderByDesc('total_gasto')
            ->get();

        $categoriaMaiorGasto = $gastosPorCategoria->first();
        $maxCategoriaGasto = $gastosPorCategoria->max('total_gasto') ?: 1;

        $html = view('relatorios.relatorio_despesas_mensal', [
            'mes' => sprintf('%02d', $mes),
            'ano' => $ano,
            'totalGasto' => $totalGasto,
            'qtdDespesas' => $qtdDespesas,
            'custoMedio' => $custoMedio,
            'despesasMaisCaras' => $despesasMaisCaras,
            'gastosPorCategoria' => $gastosPorCategoria,
            'categoriaMaiorGasto' => $categoriaMaiorGasto,
            'maxCategoriaGasto' => $maxCategoriaGasto,
        ])->render();

        $mpdf = $this->createMpdf("Relatório de Custo Mensal de Despesas - {$mes}/{$ano}");
        $mpdf->WriteHTML($html);

        return $this->pdfResponse(
            $mpdf->Output('relatorio_despesas_custo_mensal.pdf', 'S'),
            "relatorio_despesas_custo_mensal_{$mes}_{$ano}.pdf"
        );
    }

    /**
     * 2. Relatório de Balanço Mensal: Receitas vs Despesas
     */
    public function relatorioBalancoMensal(Request $request): Response
    {
        $mes = (int) $request->input('mes', date('m'));
        $ano = (int) $request->input('ano', date('Y'));

        $queryDespesas = Despesa::query()->with(['tipoDespesa', 'plano']);
        $queryRendas = Renda::query()->with('user');

        if (Auth::check()) {
            $userId = Auth::id();
            $queryDespesas->whereHas('plano', fn ($q) => $q->where('user_id', $userId));
            $queryRendas->where('user_id', $userId);
        }

        $countDespesas = (clone $queryDespesas)->whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)->count();
        if ($countDespesas === 0) {
            $latest = (clone $queryDespesas)->orderByDesc('data_vencimento')->first();
            if ($latest && $latest->data_vencimento) {
                $date = \Carbon\Carbon::parse($latest->data_vencimento);
                $mes = (int) $date->format('m');
                $ano = (int) $date->format('Y');
            }
        }

        $despesas = (clone $queryDespesas)
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->get();

        $rendas = (clone $queryRendas)->get();

        $totalDespesas = (float) $despesas->sum('valor_documento');
        $totalReceitas = (float) $rendas->sum('saldo');
        $saldoLiquido = $totalReceitas - $totalDespesas;
        $pctComprometimento = $totalReceitas > 0 ? ($totalDespesas / $totalReceitas) * 100 : 0.0;

        $html = view('relatorios.relatorio_balanco_mensal', [
            'mes' => sprintf('%02d', $mes),
            'ano' => $ano,
            'rendas' => $rendas,
            'despesas' => $despesas,
            'totalReceitas' => $totalReceitas,
            'totalDespesas' => $totalDespesas,
            'saldoLiquido' => $saldoLiquido,
            'pctComprometimento' => $pctComprometimento,
        ])->render();

        $mpdf = $this->createMpdf("Balanço Mensal Receitas vs Despesas - {$mes}/{$ano}");
        $mpdf->WriteHTML($html);

        return $this->pdfResponse(
            $mpdf->Output('relatorio_balanco_mensal.pdf', 'S'),
            "relatorio_balanco_mensal_{$mes}_{$ano}.pdf"
        );
    }

    /**
     * 3. Relatório Analítico por Categorias
     */
    public function relatorioCategorias(Request $request): Response
    {
        $mes = (int) $request->input('mes', date('m'));
        $ano = (int) $request->input('ano', date('Y'));

        $query = Despesa::query()->with(['tipoDespesa', 'plano']);

        if (Auth::check()) {
            $query->whereHas('plano', fn ($q) => $q->where('user_id', Auth::id()));
        }

        $countCurrent = (clone $query)->whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)->count();
        if ($countCurrent === 0) {
            $latest = (clone $query)->orderByDesc('data_vencimento')->first();
            if ($latest && $latest->data_vencimento) {
                $date = \Carbon\Carbon::parse($latest->data_vencimento);
                $mes = (int) $date->format('m');
                $ano = (int) $date->format('Y');
            }
        }

        $query->whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano);

        $gastosPorCategoria = (clone $query)
            ->select(
                'tipo_despesa_id',
                DB::raw('SUM(valor_documento) as total_gasto'),
                DB::raw('COUNT(*) as total_itens')
            )
            ->groupBy('tipo_despesa_id')
            ->with('tipoDespesa')
            ->orderByDesc('total_gasto')
            ->get();

        $totalGasto = (float) $gastosPorCategoria->sum('total_gasto');
        $totalCategorias = $gastosPorCategoria->count();
        $mediaPorCategoria = $totalCategorias > 0 ? ($totalGasto / $totalCategorias) : 0.0;

        $categoriaMaiorGasto = $gastosPorCategoria->first();
        $maxCategoriaGasto = $gastosPorCategoria->max('total_gasto') ?: 1;

        $html = view('relatorios.relatorio_categorias', [
            'mes' => sprintf('%02d', $mes),
            'ano' => $ano,
            'gastosPorCategoria' => $gastosPorCategoria,
            'totalGasto' => $totalGasto,
            'mediaPorCategoria' => $mediaPorCategoria,
            'categoriaMaiorGasto' => $categoriaMaiorGasto,
            'maxCategoriaGasto' => $maxCategoriaGasto,
        ])->render();

        $mpdf = $this->createMpdf("Análise de Gastos por Categoria - {$mes}/{$ano}");
        $mpdf->WriteHTML($html);

        return $this->pdfResponse(
            $mpdf->Output('relatorio_categorias.pdf', 'S'),
            "relatorio_categorias_{$mes}_{$ano}.pdf"
        );
    }

    /**
     * 4. Relatório de Auditoria e Status de Despesas
     */
    public function relatorioAuditoriaDespesas(Request $request): Response
    {
        $mes = (int) $request->input('mes', date('m'));
        $ano = (int) $request->input('ano', date('Y'));

        $query = Despesa::query()->with(['tipoDespesa', 'statusDespesa', 'plano']);

        if (Auth::check()) {
            $query->whereHas('plano', fn ($q) => $q->where('user_id', Auth::id()));
        }

        $countCurrent = (clone $query)->whereMonth('data_vencimento', $mes)->whereYear('data_vencimento', $ano)->count();
        if ($countCurrent === 0) {
            $latest = (clone $query)->orderByDesc('data_vencimento')->first();
            if ($latest && $latest->data_vencimento) {
                $date = \Carbon\Carbon::parse($latest->data_vencimento);
                $mes = (int) $date->format('m');
                $ano = (int) $date->format('Y');
            }
        }

        $despesas = (clone $query)
            ->whereMonth('data_vencimento', $mes)
            ->whereYear('data_vencimento', $ano)
            ->get();

        $historicos = HistoricoDespesa::with(['despesa', 'statusDespesa'])
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        $totalDespesas = $despesas->count();
        $qtdPagas = $despesas->filter(fn ($d) => str_contains(mb_strtoupper($d->statusDespesa->nome ?? ''), 'PAG'))->count();
        $qtdPendentes = $totalDespesas - $qtdPagas;

        $html = view('relatorios.relatorio_auditoria_despesas', [
            'mes' => sprintf('%02d', $mes),
            'ano' => $ano,
            'despesas' => $despesas,
            'historicos' => $historicos,
            'totalDespesas' => $totalDespesas,
            'qtdPagas' => $qtdPagas,
            'qtdPendentes' => $qtdPendentes,
        ])->render();

        $mpdf = $this->createMpdf("Relatório de Auditoria e Status - {$mes}/{$ano}");
        $mpdf->WriteHTML($html);

        return $this->pdfResponse(
            $mpdf->Output('relatorio_auditoria_despesas.pdf', 'S'),
            "relatorio_auditoria_despesas_{$mes}_{$ano}.pdf"
        );
    }

    /**
     * 5. Relatório Gerencial de Produtos e Estoque
     */
    public function relatorioProdutos(Request $request): Response
    {
        $query = Produto::query()->with('user');

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        $produtos = $query->get();

        $totalQuantidade = (float) $produtos->sum('quantidade');
        $valorTotalEstoque = (float) $produtos->sum(fn ($p) => (float) $p->preco * (float) $p->quantidade);
        $precoMedio = $produtos->count() > 0 ? ((float) $produtos->avg('preco')) : 0.0;

        $html = view('relatorios.relatorio_produtos', [
            'produtos' => $produtos,
            'totalQuantidade' => $totalQuantidade,
            'valorTotalEstoque' => $valorTotalEstoque,
            'precoMedio' => $precoMedio,
        ])->render();

        $mpdf = $this->createMpdf('Relatório Gerencial de Produtos e Estoque');
        $mpdf->WriteHTML($html);

        return $this->pdfResponse(
            $mpdf->Output('relatorio_produtos.pdf', 'S'),
            'relatorio_produtos.pdf'
        );
    }
}
