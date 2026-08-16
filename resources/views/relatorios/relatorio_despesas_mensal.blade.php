<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Custo Mensal de Despesas</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }

        .header-banner {
            background-color: #0f172a;
            color: #ffffff;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }

        .kpi-container {
            width: 100%;
            margin-bottom: 25px;
        }

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            text-align: left;
        }

        .kpi-card-title {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .kpi-card-value {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
        }

        .kpi-card-highlight {
            color: #2563eb;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 6px;
            margin-top: 20px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .chart-container {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .bar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bar-table td {
            padding: 6px 4px;
            vertical-align: middle;
        }

        .bar-label {
            width: 28%;
            font-weight: bold;
            font-size: 11px;
            color: #334155;
        }

        .bar-wrapper {
            width: 52%;
        }

        .bar-bg {
            background-color: #f1f5f9;
            border-radius: 4px;
            height: 18px;
            width: 100%;
            overflow: hidden;
        }

        .bar-fill {
            height: 18px;
            border-radius: 4px;
            width: var(--bar-width, 0%);
            background-color: var(--bar-color, #2563eb);
        }

        .bar-value {
            width: 20%;
            text-align: right;
            font-weight: bold;
            font-size: 11px;
            color: #0f172a;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #cbd5e1;
        }

        .data-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .badge-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #e2e8f0;
            color: #475569;
        }

        .badge-pago {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-pendente {
            background-color: #fef9c3;
            color: #854d0e;
        }

        .badge-atrasado {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .empty-state {
            padding: 20px;
            text-align: center;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>

<body>

    <!-- Header Banner -->
    <div class="header-banner">
        <div class="header-title">Meu Financeiro</div>
        <div class="header-subtitle">Relatório Gerencial de Custo Mensal de Despesas — Referência: {{ $mes }}/{{ $ano }}</div>
    </div>

    <!-- KPI Summary Grid -->
    <div class="kpi-container">
        <table class="kpi-table">
            <tr>
                <td class="kpi-card" style="width: 25%;">
                    <div class="kpi-card-title">Custo Médio por Despesa</div>
                    <div class="kpi-card-value kpi-card-highlight">
                        R$ {{ number_format($custoMedio, 2, ',', '.') }}
                    </div>
                </td>
                <td class="kpi-card" style="width: 25%;">
                    <div class="kpi-card-title">Total Gasto no Mês</div>
                    <div class="kpi-card-value">
                        R$ {{ number_format($totalGasto, 2, ',', '.') }}
                    </div>
                </td>
                <td class="kpi-card" style="width: 25%;">
                    <div class="kpi-card-title">Categoria Maior Gasto</div>
                    <div class="kpi-card-value" style="font-size: 13px;">
                        {{ $categoriaMaiorGasto->tipoDespesa->nome ?? 'N/A' }}
                    </div>
                </td>
                <td class="kpi-card" style="width: 25%;">
                    <div class="kpi-card-title">Qtd. de Despesas</div>
                    <div class="kpi-card-value">
                        {{ $qtdDespesas }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Section: Gráfico de Barras por Categoria -->
    <div class="section-title">📊 Comparação de Gastos por Categoria</div>
    <div class="chart-container">
        @if ($gastosPorCategoria->isNotEmpty())
        <table class="bar-table">
            @foreach ($gastosPorCategoria as $index => $cat)
            @php
            $catNome = $cat->tipoDespesa->nome ?? 'OUTROS';
            $pctRelativa = $maxCategoriaGasto > 0 ? ($cat->total_gasto / $maxCategoriaGasto) * 100 : 0;
            $pctTotal = $totalGasto > 0 ? ($cat->total_gasto / $totalGasto) * 100 : 0;
            $colorHex = match ($index % 4) {
                0 => '#2563eb', // Blue
                1 => '#0d9488', // Teal
                2 => '#7c3aed', // Purple
                3 => '#ea580c', // Orange
                default => '#2563eb',
            };
            $widthPct = max($pctRelativa, 3);
            @endphp
            <tr>
                <td class="bar-label">{{ $catNome }}</td>
                <td class="bar-wrapper">
                    <div class="bar-bg">
                        <div class="bar-fill" style="--bar-width: {{ $widthPct }}%; --bar-color: {{ $colorHex }};"></div>
                    </div>
                </td>
                <td class="bar-value">
                    R$ {{ number_format($cat->total_gasto, 2, ',', '.') }}
                    <br>
                    <span style="font-size: 9px; color: #64748b; font-weight: normal;">({{ number_format($pctTotal, 1, ',', '.') }}%)</span>
                </td>
            </tr>
            @endforeach
        </table>
        @else
        <div class="empty-state">Nenhuma despesa encontrada no período selecionado.</div>
        @endif
    </div>

    <!-- Section: Despesas Mais Caras (Top 5) -->
    <div class="section-title">🔥 Despesas Mais Caras do Mês (Top {{ $despesasMaisCaras->count() }})</div>
    @if ($despesasMaisCaras->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">#</th>
                <th style="width: 37%;">Descrição</th>
                <th style="width: 22%;">Categoria</th>
                <th style="width: 15%;">Data Venc.</th>
                <th style="width: 18%;" class="text-right">Valor Documento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($despesasMaisCaras as $index => $despesa)
            <tr>
                <td class="text-center" style="font-weight: bold; color: #64748b;">{{ $index + 1 }}º</td>
                <td style="font-weight: bold; color: #0f172a;">{{ $despesa->descricao }}</td>
                <td>{{ $despesa->tipoDespesa->nome ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</td>
                <td class="text-right" style="font-weight: bold; color: #b91c1c;">
                    R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhuma despesa encontrada.</div>
    @endif

    <!-- Section: Detalhamento por Categoria -->
    <div class="section-title">📑 Detalhamento Completo por Categoria</div>
    @if ($gastosPorCategoria->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th>Categoria</th>
                <th class="text-center">Qtd. Itens</th>
                <th class="text-right">Custo Médio p/ Categoria</th>
                <th class="text-right">% do Total</th>
                <th class="text-right">Total Acumulado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gastosPorCategoria as $cat)
            @php
            $catNome = $cat->tipoDespesa->nome ?? 'OUTROS';
            $mediaCat = $cat->total_itens > 0 ? ($cat->total_gasto / $cat->total_itens) : 0;
            $pctTotal = $totalGasto > 0 ? ($cat->total_gasto / $totalGasto) * 100 : 0;
            @endphp
            <tr>
                <td style="font-weight: bold;">{{ $catNome }}</td>
                <td class="text-center">{{ $cat->total_itens }}</td>
                <td class="text-right">R$ {{ number_format($mediaCat, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($pctTotal, 1, ',', '.') }}%</td>
                <td class="text-right" style="font-weight: bold;">R$ {{ number_format($cat->total_gasto, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</body>

</html>
