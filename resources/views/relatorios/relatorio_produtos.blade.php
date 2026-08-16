<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório Gerencial de Produtos & Estoque</title>
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
        <div class="header-subtitle">Relatório Gerencial de Produtos e Avaliação de Estoque — Data de Emissão: {{ date('d/m/Y') }}</div>
    </div>

    <!-- KPI Table -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Produtos Cadastrados</div>
                <div class="kpi-value" style="color: #2563eb;">{{ $produtos->count() }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Quantidade Em Estoque</div>
                <div class="kpi-value">{{ number_format($totalQuantidade, 0, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Preço Médio por Item</div>
                <div class="kpi-value">R$ {{ number_format($precoMedio, 2, ',', '.') }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Valor Total do Estoque</div>
                <div class="kpi-value" style="color: #166534;">R$ {{ number_format($valorTotalEstoque, 2, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <!-- Tabela de Produtos -->
    <div class="section-title">📦 Catálogo Geral de Produtos</div>
    @if ($produtos->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;"># ID</th>
                <th style="width: 38%;">Descrição / Item</th>
                <th style="width: 14%;" class="text-center">Qtd. / Unidade</th>
                <th style="width: 18%;" class="text-right">Preço Unitário</th>
                <th style="width: 22%;" class="text-right">Total Acumulado (R$)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produtos as $produto)
            @php
            $itemTotal = (float) ($produto->preco * $produto->quantidade);
            @endphp
            <tr>
                <td>#{{ $produto->id }}</td>
                <td style="font-weight: bold; color: #0f172a;">{{ $produto->descricao_curta }}</td>
                <td class="text-center">{{ $produto->quantidade }} {{ $produto->tipo_medida ?? 'UN' }}</td>
                <td class="text-right">R$ {{ number_format((float)$produto->preco, 2, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold; color: #166534;">
                    R$ {{ number_format($itemTotal, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhum produto cadastrado no catálogo.</div>
    @endif

</body>

</html>
