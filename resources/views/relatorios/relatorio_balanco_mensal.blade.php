<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Balanço Mensal: Receitas vs Despesas</title>
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

        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 25px;
        }

        .kpi-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 15px;
            text-align: left;
        }

        .kpi-title {
            font-size: 10px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .kpi-value {
            font-size: 16px;
            font-weight: bold;
        }

        .text-green { color: #166534; }
        .text-red { color: #b91c1c; }
        .text-blue { color: #2563eb; }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
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

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .empty-state {
            padding: 15px;
            text-align: center;
            color: #64748b;
            font-style: italic;
        }

        .balance-summary {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <!-- Header Banner -->
    <div class="header-banner">
        <div class="header-title">Meu Financeiro</div>
        <div class="header-subtitle">Demonstrativo de Balanço Mensal (Receitas vs. Despesas) — Referência: {{ $mes }}/{{ $ano }}</div>
    </div>

    <!-- KPI Table -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Receitas / Rendas</div>
                <div class="kpi-value text-green">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Saídas / Despesas</div>
                <div class="kpi-value text-red">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Saldo Líquido Mensal</div>
                <div class="kpi-value {{ $saldoLiquido >= 0 ? 'text-green' : 'text-red' }}">
                    R$ {{ number_format($saldoLiquido, 2, ',', '.') }}
                </div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">% Comprometimento</div>
                <div class="kpi-value text-blue">{{ number_format($pctComprometimento, 1, ',', '.') }}%</div>
            </td>
        </tr>
    </table>

    <!-- Quadro Resumo -->
    <div class="balance-summary">
        <strong style="font-size: 12px; color: #0f172a;">📊 Análise de Comprometimento Financeiro:</strong>
        <p style="margin: 5px 0 0 0; font-size: 11px; color: #334155;">
            Do total de rendas cadastradas (<strong>R$ {{ number_format($totalReceitas, 2, ',', '.') }}</strong>), 
            <strong>{{ number_format($pctComprometimento, 1, ',', '.') }}%</strong> foram comprometidos com despesas.
            O resultado final do período é um saldo 
            <strong class="{{ $saldoLiquido >= 0 ? 'text-green' : 'text-red' }}">
                {{ $saldoLiquido >= 0 ? 'POSITIVO' : 'DEFICITÁRIO' }} de R$ {{ number_format(abs($saldoLiquido), 2, ',', '.') }}
            </strong>.
        </p>
    </div>

    <!-- Tabela de Receitas / Rendas -->
    <div class="section-title">💵 Rendas e Receitas Registradas</div>
    @if ($rendas->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;"># ID</th>
                <th>Usuário / Titular</th>
                <th class="text-right">Custo / Origem</th>
                <th class="text-right">Valor Saldo (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rendas as $renda)
            <tr>
                <td>#{{ $renda->id }}</td>
                <td>{{ $renda->user->name ?? 'Geral' }}</td>
                <td class="text-right">R$ {{ number_format($renda->custo ?? 0, 2, ',', '.') }}</td>
                <td class="text-right text-green" style="font-weight: bold;">R$ {{ number_format($renda->saldo, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhuma renda cadastrada no sistema para este período.</div>
    @endif

    <!-- Tabela de Despesas Registradas -->
    <div class="section-title">💸 Resumo de Despesas no Período (Total: {{ $despesas->count() }})</div>
    @if ($despesas->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 38%;">Descrição</th>
                <th style="width: 22%;">Categoria</th>
                <th style="width: 14%;">Vencimento</th>
                <th class="text-right" style="width: 18%;">Valor (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($despesas as $index => $despesa)
            <tr>
                <td>{{ $index + 1 }}º</td>
                <td style="font-weight: bold; color: #0f172a;">{{ $despesa->descricao }}</td>
                <td>{{ $despesa->tipoDespesa->nome ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</td>
                <td class="text-right text-red" style="font-weight: bold;">R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhuma despesa registrada para o período selecionado.</div>
    @endif

</body>

</html>
