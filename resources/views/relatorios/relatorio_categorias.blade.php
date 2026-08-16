<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Análise Comparativa por Categorias</title>
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
            color: #0f172a;
        }

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

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .empty-state {
            padding: 15px;
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
        <div class="header-subtitle">Relatório Analítico de Gastos por Categoria — Referência: {{ $mes }}/{{ $ano }}</div>
    </div>

    <!-- KPI Table -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Categorias com Gastos</div>
                <div class="kpi-value" style="color: #2563eb;">{{ $gastosPorCategoria->count() }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total Gasto no Período</div>
                <div class="kpi-value">R$ {{ number_format($totalGasto, 2, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Média por Categoria</div>
                <div class="kpi-value">R$ {{ number_format($mediaPorCategoria, 2, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Maior Categoria</div>
                <div class="kpi-value" style="font-size: 13px; color: #d97706;">
                    {{ $categoriaMaiorGasto->tipoDespesa->nome ?? 'N/A' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Gráfico de Barras -->
    <div class="section-title">📊 Distribuição Comparativa por Categoria</div>
    <div class="chart-container">
        @if ($gastosPorCategoria->isNotEmpty())
        <table class="bar-table">
            @foreach ($gastosPorCategoria as $index => $cat)
            @php
            $catNome = $cat->tipoDespesa->nome ?? 'OUTROS';
            $pctRelativa = $maxCategoriaGasto > 0 ? ($cat->total_gasto / $maxCategoriaGasto) * 100 : 0;
            $pctTotal = $totalGasto > 0 ? ($cat->total_gasto / $totalGasto) * 100 : 0;
            $colorHex = match ($index % 4) {
                0 => '#2563eb',
                1 => '#0d9488',
                2 => '#7c3aed',
                3 => '#ea580c',
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
        <div class="empty-state">Nenhuma categoria com gastos encontrada no período.</div>
        @endif
    </div>

    <!-- Tabela Detalhada -->
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
    @else
    <div class="empty-state">Sem dados de categorias para exibir.</div>
    @endif

</body>

</html>
