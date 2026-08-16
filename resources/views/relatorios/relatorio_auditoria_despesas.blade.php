<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Histórico & Auditoria de Status de Despesas</title>
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
        <div class="header-subtitle">Relatório de Auditoria e Status de Liquidação de Despesas — Referência: {{ $mes }}/{{ $ano }}</div>
    </div>

    <!-- KPI Table -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Total de Despesas</div>
                <div class="kpi-value">{{ $totalDespesas }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Despesas Pagas</div>
                <div class="kpi-value" style="color: #166534;">{{ $qtdPagas }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Pendentes / Atrasadas</div>
                <div class="kpi-value" style="color: #b91c1c;">{{ $qtdPendentes }}</div>
            </td>
            <td class="kpi-card" style="width: 25%;">
                <div class="kpi-title">Registros no Audit Log</div>
                <div class="kpi-value" style="color: #7c3aed;">{{ $historicos->count() }}</div>
            </td>
        </tr>
    </table>

    <!-- Tabela de Auditoria de Despesas -->
    <div class="section-title">📋 Status Atual das Despesas do Período</div>
    @if ($despesas->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">#</th>
                <th style="width: 32%;">Descrição</th>
                <th style="width: 20%;">Categoria</th>
                <th style="width: 15%;">Data Venc.</th>
                <th style="width: 13%;" class="text-center">Status</th>
                <th style="width: 12%;" class="text-right">Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($despesas as $index => $despesa)
            @php
            $statusNome = mb_strtoupper($despesa->statusDespesa->nome ?? 'PENDENTE');
            $badgeClass = match (true) {
                str_contains($statusNome, 'PAG') => 'badge-pago',
                str_contains($statusNome, 'ATR') => 'badge-atrasado',
                default => 'badge-pendente',
            };
            @endphp
            <tr>
                <td>{{ $index + 1 }}º</td>
                <td style="font-weight: bold; color: #0f172a;">{{ $despesa->descricao }}</td>
                <td>{{ $despesa->tipoDespesa->nome ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($despesa->data_vencimento)->format('d/m/Y') }}</td>
                <td class="text-center">
                    <span class="badge-status {{ $badgeClass }}">{{ $statusNome }}</span>
                </td>
                <td class="text-right" style="font-weight: bold;">
                    R$ {{ number_format($despesa->valor_documento, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhuma despesa encontrada para o período informado.</div>
    @endif

    <!-- Tabela de Log de Alterações (Audit Log) -->
    <div class="section-title">📜 Histórico Recente de Alterações de Status (Audit Log)</div>
    @if ($historicos->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 10%;"># Audit</th>
                <th style="width: 45%;">Despesa Relacionada</th>
                <th style="width: 25%;">Status Registrado</th>
                <th style="width: 20%;" class="text-right">Data do Evento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($historicos as $hist)
            <tr>
                <td>#{{ $hist->id }}</td>
                <td style="font-weight: bold;">{{ $hist->despesa->descricao ?? 'Despesa #' . $hist->despesa_id }}</td>
                <td>
                    <span class="badge-status badge-pago">{{ $hist->statusDespesa->nome ?? 'Atualizado' }}</span>
                </td>
                <td class="text-right">
                    {{ $hist->data ? \Carbon\Carbon::parse($hist->data)->format('d/m/Y') : $hist->created_at->format('d/m/Y H:i') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">Nenhum evento registrado no histórico de auditoria.</div>
    @endif

</body>

</html>
