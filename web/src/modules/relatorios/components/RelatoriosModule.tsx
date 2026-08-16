'use client';

import React, { useState, useEffect } from 'react';
import {
  FileText,
  Download,
  Eye,
  Calendar,
  Filter,
  FileBarChart,
  CheckCircle2,
  AlertCircle,
  Loader2,
  RefreshCw,
  Printer,
  ExternalLink,
  X,
  Sparkles,
  PieChart,
  TrendingDown,
  ShieldCheck,
} from 'lucide-react';
import { relatoriosService, AVAILABLE_REPORTS, ReportItem } from '../services/relatoriosService';

const MESES = [
  { value: 1, label: 'Janeiro' },
  { value: 2, label: 'Fevereiro' },
  { value: 3, label: 'Março' },
  { value: 4, label: 'Abril' },
  { value: 5, label: 'Maio' },
  { value: 6, label: 'Junho' },
  { value: 7, label: 'Julho' },
  { value: 8, label: 'Agosto' },
  { value: 9, label: 'Setembro' },
  { value: 10, label: 'Outubro' },
  { value: 11, label: 'Novembro' },
  { value: 12, label: 'Dezembro' },
];

const ANOS = [2024, 2025, 2026, 2027];

export default function RelatoriosModule() {
  const currentDate = new Date();
  const [selectedMes, setSelectedMes] = useState<number>(2); // Fevereiro (Mês com despesas registradas)
  const [selectedAno, setSelectedAno] = useState<number>(2026);

  // PDF Preview State
  const [previewReport, setPreviewReport] = useState<ReportItem | null>(null);
  const [pdfUrl, setPdfUrl] = useState<string | null>(null);
  const [loadingPreview, setLoadingPreview] = useState<boolean>(false);
  const [loadingDownloadId, setLoadingDownloadId] = useState<string | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  // Limpa o ObjectURL ao fechar o preview
  useEffect(() => {
    return () => {
      if (pdfUrl) {
        URL.revokeObjectURL(pdfUrl);
      }
    };
  }, [pdfUrl]);

  // Função para abrir modal de preview
  async function handleOpenPreview(report: ReportItem) {
    try {
      setLoadingPreview(true);
      setErrorMessage(null);
      setPreviewReport(report);

      const blob = await relatoriosService.getRelatorioPdfBlob(report.endpoint, selectedMes, selectedAno);
      const url = URL.createObjectURL(blob);
      setPdfUrl(url);
    } catch (err: any) {
      setErrorMessage(err.message || 'Falha ao carregar a pré-visualização do relatório.');
      setPdfUrl(null);
    } finally {
      setLoadingPreview(false);
    }
  }

  // Função para fechar modal de preview
  function handleClosePreview() {
    if (pdfUrl) {
      URL.revokeObjectURL(pdfUrl);
      setPdfUrl(null);
    }
    setPreviewReport(null);
    setErrorMessage(null);
  }

  // Função para download direto
  async function handleDownload(report: ReportItem) {
    try {
      setLoadingDownloadId(report.id);
      setErrorMessage(null);
      await relatoriosService.downloadRelatorioPdf(report, selectedMes, selectedAno);
    } catch (err: any) {
      setErrorMessage(err.message || 'Falha ao realizar o download do relatório PDF.');
    } finally {
      setLoadingDownloadId(null);
    }
  }

  // Atalho para mês atual
  function setPeriodoAtual() {
    setSelectedMes(currentDate.getMonth() + 1);
    setSelectedAno(currentDate.getFullYear());
  }

  return (
    <div className="space-y-6">
      {/* Header do Módulo de Relatórios */}
      <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl shadow-xl relative overflow-hidden">
        <div className="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none" />
        
        <div className="space-y-1 relative z-10">
          <div className="flex items-center gap-2.5">
            <div className="w-10 h-10 bg-indigo-600/20 border border-indigo-500/40 rounded-xl flex items-center justify-center text-indigo-400 shadow-inner">
              <FileBarChart className="w-5 h-5" />
            </div>
            <div>
              <h1 className="text-xl font-bold text-slate-100 tracking-tight flex items-center gap-2">
                Módulo de Relatórios Gerenciais
                <span className="text-[10px] font-extrabold bg-indigo-500/20 text-indigo-400 border border-indigo-500/30 px-2 py-0.5 rounded-full uppercase tracking-wider">
                  Enterprise PDF
                </span>
              </h1>
              <p className="text-xs text-slate-400">
                Geração, exportação e análise consolidada de relatórios financeiros e custos do sistema
              </p>
            </div>
          </div>
        </div>

        {/* Resumo Rápido dos Filtros */}
        <div className="flex items-center gap-3 bg-slate-950/80 border border-slate-800 p-3 rounded-xl relative z-10">
          <Calendar className="w-4 h-4 text-indigo-400" />
          <div className="text-xs">
            <span className="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Período de Referência</span>
            <strong className="text-slate-200">
              {MESES.find((m) => m.value === selectedMes)?.label} de {selectedAno}
            </strong>
          </div>
        </div>
      </div>

      {/* Barra de Filtros e Seleção de Período */}
      <div className="bg-slate-900 border border-slate-800 p-5 rounded-2xl shadow-lg space-y-4">
        <div className="flex items-center justify-between border-b border-slate-800 pb-3">
          <div className="flex items-center gap-2 text-xs font-bold text-slate-300 uppercase tracking-wider">
            <Filter className="w-4 h-4 text-indigo-400" />
            Filtros do Relatório
          </div>
          <button
            type="button"
            onClick={setPeriodoAtual}
            className="text-xs text-indigo-400 hover:text-indigo-300 font-medium flex items-center gap-1.5 transition-colors"
          >
            <RefreshCw className="w-3.5 h-3.5" /> Usar Mês Atual
          </button>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          {/* Mês */}
          <div>
            <label className="block text-xs font-semibold text-slate-400 mb-1.5">Mês de Referência</label>
            <select
              value={selectedMes}
              onChange={(e) => setSelectedMes(Number(e.target.value))}
              className="w-full bg-slate-950 border border-slate-800 text-slate-200 text-xs rounded-xl p-3 focus:outline-none focus:border-indigo-500/60 focus:ring-1 focus:ring-indigo-500/60 transition-all"
            >
              {MESES.map((m) => (
                <option key={m.value} value={m.value}>
                  {m.label} ({m.value.toString().padStart(2, '0')})
                </option>
              ))}
            </select>
          </div>

          {/* Ano */}
          <div>
            <label className="block text-xs font-semibold text-slate-400 mb-1.5">Ano de Referência</label>
            <select
              value={selectedAno}
              onChange={(e) => setSelectedAno(Number(e.target.value))}
              className="w-full bg-slate-950 border border-slate-800 text-slate-200 text-xs rounded-xl p-3 focus:outline-none focus:border-indigo-500/60 focus:ring-1 focus:ring-indigo-500/60 transition-all"
            >
              {ANOS.map((a) => (
                <option key={a} value={a}>
                  {a}
                </option>
              ))}
            </select>
          </div>

          {/* Ação Rápida */}
          <div className="flex items-end">
            <button
              type="button"
              onClick={() => handleOpenPreview(AVAILABLE_REPORTS[0])}
              className="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs py-3 px-4 rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center justify-center gap-2"
            >
              <Sparkles className="w-4 h-4" /> Gerar Relatório PDF Agora
            </button>
          </div>
        </div>
      </div>

      {/* Alertas de Erro */}
      {errorMessage && (
        <div className="bg-rose-950/50 border border-rose-500/40 text-rose-300 p-4 rounded-xl text-xs flex items-center gap-3 animate-fadeIn">
          <AlertCircle className="w-5 h-5 text-rose-400 shrink-0" />
          <div className="flex-1">{errorMessage}</div>
          <button onClick={() => setErrorMessage(null)} className="text-rose-400 hover:text-rose-200">
            <X className="w-4 h-4" />
          </button>
        </div>
      )}

      {/* Catálogo de Relatórios */}
      <div className="space-y-4">
        <h2 className="text-sm font-bold text-slate-300 uppercase tracking-wider flex items-center gap-2">
          <FileText className="w-4 h-4 text-indigo-400" /> Catálogo de Relatórios Disponíveis
        </h2>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
          {AVAILABLE_REPORTS.map((report) => {
            const isDownloading = loadingDownloadId === report.id;

            return (
              <div
                key={report.id}
                className="bg-slate-900 border border-slate-800 hover:border-indigo-500/40 p-5 rounded-2xl shadow-md transition-all duration-300 flex flex-col justify-between group hover:shadow-xl hover:shadow-indigo-500/5 relative overflow-hidden"
              >
                <div className="space-y-3">
                  <div className="flex items-start justify-between gap-3">
                    <div className="w-10 h-10 bg-slate-950 border border-slate-800 rounded-xl flex items-center justify-center text-indigo-400 group-hover:border-indigo-500/40 group-hover:text-indigo-300 transition-colors">
                      <FileBarChart className="w-5 h-5" />
                    </div>
                    <span className={`text-[10px] font-bold border px-2.5 py-1 rounded-full uppercase tracking-wider ${report.badgeColor}`}>
                      {report.badge}
                    </span>
                  </div>

                  <div>
                    <span className="text-[10px] uppercase font-bold text-slate-500 tracking-wider">
                      {report.category}
                    </span>
                    <h3 className="text-sm font-bold text-slate-100 group-hover:text-indigo-400 transition-colors">
                      {report.title}
                    </h3>
                    <p className="text-xs text-slate-400 mt-1 line-clamp-2">
                      {report.description}
                    </p>
                  </div>
                </div>

                <div className="mt-5 pt-4 border-t border-slate-800/80 flex items-center justify-between gap-3">
                  <div className="flex items-center gap-2 text-[11px] text-slate-500 font-medium">
                    <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" /> Formato: {report.format}
                  </div>

                  <div className="flex items-center gap-2">
                    <button
                      type="button"
                      onClick={() => handleOpenPreview(report)}
                      disabled={loadingPreview}
                      className="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 hover:border-slate-600 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 disabled:opacity-50"
                      title="Visualizar relatório em tela"
                    >
                      <Eye className="w-3.5 h-3.5 text-indigo-400" /> Visualizar
                    </button>

                    <button
                      type="button"
                      onClick={() => handleDownload(report)}
                      disabled={isDownloading}
                      className="bg-indigo-600/20 hover:bg-indigo-600/30 text-indigo-300 border border-indigo-500/40 hover:border-indigo-500/70 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 disabled:opacity-50"
                      title="Baixar PDF do relatório"
                    >
                      {isDownloading ? (
                        <Loader2 className="w-3.5 h-3.5 animate-spin" />
                      ) : (
                        <Download className="w-3.5 h-3.5 text-indigo-400" />
                      )}
                      Baixar PDF
                    </button>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      </div>

      {/* Modal de Pré-Visualização do PDF */}
      {previewReport && (
        <div className="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 md:p-6 animate-fadeIn">
          <div className="bg-slate-900 border border-slate-800 rounded-2xl w-full max-w-5xl h-[88vh] flex flex-col shadow-2xl overflow-hidden">
            {/* Modal Header */}
            <div className="h-16 px-6 bg-slate-900 border-b border-slate-800 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-9 h-9 bg-indigo-600/20 border border-indigo-500/40 rounded-lg flex items-center justify-center text-indigo-400">
                  <FileText className="w-5 h-5" />
                </div>
                <div>
                  <h3 className="text-sm font-bold text-slate-100 flex items-center gap-2">
                    {previewReport.title}
                    <span className="text-[10px] bg-slate-800 text-slate-400 border border-slate-700 px-2 py-0.5 rounded">
                      {MESES.find((m) => m.value === selectedMes)?.label}/{selectedAno}
                    </span>
                  </h3>
                  <p className="text-[11px] text-slate-400">Pré-visualização do documento PDF renderizado em tempo real</p>
                </div>
              </div>

              <div className="flex items-center gap-2">
                {pdfUrl && (
                  <>
                    <button
                      onClick={() => handleDownload(previewReport)}
                      className="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 shadow-md shadow-indigo-600/20"
                    >
                      <Download className="w-3.5 h-3.5" /> Baixar PDF
                    </button>

                    <a
                      href={pdfUrl}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5"
                    >
                      <ExternalLink className="w-3.5 h-3.5" /> Nova Aba
                    </a>
                  </>
                )}

                <button
                  onClick={handleClosePreview}
                  className="bg-slate-800 hover:bg-rose-950/60 text-slate-400 hover:text-rose-400 p-2 rounded-lg transition-colors border border-slate-700 hover:border-rose-500/40"
                  title="Fechar pré-visualização"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
            </div>

            {/* Modal Body / PDF Iframe */}
            <div className="flex-1 bg-slate-950 relative flex items-center justify-center">
              {loadingPreview ? (
                <div className="flex flex-col items-center gap-3 text-slate-400">
                  <Loader2 className="w-8 h-8 text-indigo-400 animate-spin" />
                  <span className="text-xs font-semibold">Gerando relatório PDF... Aguarde um instante</span>
                </div>
              ) : pdfUrl ? (
                <iframe
                  src={pdfUrl}
                  title="Visualização do Relatório PDF"
                  className="w-full h-full border-0 rounded-b-2xl"
                />
              ) : (
                <div className="text-center text-slate-400 space-y-2 p-6">
                  <AlertCircle className="w-8 h-8 text-rose-400 mx-auto" />
                  <p className="text-xs font-semibold">Não foi possível carregar a visualização do documento.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
