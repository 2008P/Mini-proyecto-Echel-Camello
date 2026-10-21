import React, { useState } from 'react';
import { Solicitud, User, EstadoSolicitudEnum, AppScreen } from '../types';
import { SolicitudStateMachine } from '../patterns/state/SolicitudStateMachine';

interface RequestsManagerViewProps {
  currentUser: User | null;
  setScreen: (screen: AppScreen) => void;
  onOpenChatWith: (workerId: string) => void;
}

const INITIAL_SOLICITUDES: Solicitud[] = [
  {
    id: 'sol-001',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-1',
    trabajadorNombre: 'Camilo Restrepo',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
    trabajadorProfesion: 'Desarrollador Fullstack Cloud',
    titulo: 'Desarrollo de Módulo de Pagos con Pasarela Wompi',
    descripcion: 'Necesitamos integrar la pasarela de pagos Wompi y PSE en nuestra plataforma web con webhook seguro.',
    estado: 'PROGRAMADA',
    fechaProgramada: '2026-10-25 09:30',
    direccion: 'Modalidad Remota (Google Meet)',
    valorPropuesto: 3500000,
    valorAcordado: 3500000,
    createdAt: '2026-10-21 11:30'
  },
  {
    id: 'sol-002',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-2',
    trabajadorNombre: 'Valentina Gómez',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&q=80',
    trabajadorProfesion: 'Diseñadora UX/UI & Tokens',
    titulo: 'Diseño de Interfaz Móvil y Prototipo Figma',
    descripcion: 'Rediseño completo del flujo de onboarding y dashboard de usuario para app iOS y Android.',
    estado: 'EN_PROCESO',
    fechaProgramada: '2026-10-22 14:00',
    direccion: 'Remoto / Figma Workspace',
    valorPropuesto: 2800000,
    valorAcordado: 2800000,
    createdAt: '2026-10-21 12:00'
  },
  {
    id: 'sol-003',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-1',
    trabajadorNombre: 'Camilo Restrepo',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
    trabajadorProfesion: 'Desarrollador Fullstack Cloud',
    titulo: 'Auditoría de Rendimiento Web y Core Web Vitals',
    descripcion: 'Diagnóstico y optimización para alcanzar puntaje 95+ en Google PageSpeed Insights.',
    estado: 'PENDIENTE',
    direccion: 'Remoto (Asíncrono)',
    valorPropuesto: 1500000,
    createdAt: '2026-10-21 09:00'
  },
  {
    id: 'sol-004',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-2',
    trabajadorNombre: 'Valentina Gómez',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&q=80',
    trabajadorProfesion: 'Diseñadora UX/UI & Tokens',
    titulo: 'Creación de Design Tokens y Guía de Estilo',
    descripcion: 'Documentación estructurada de tokens de color, tipografía y componentes atómicos.',
    estado: 'FINALIZADA',
    fechaProgramada: '2026-10-18 10:00',
    valorPropuesto: 2200000,
    valorAcordado: 2200000,
    createdAt: '2026-10-18 10:00'
  }
];

export const RequestsManagerView: React.FC<RequestsManagerViewProps> = ({
  currentUser,
  setScreen,
  onOpenChatWith,
}) => {
  const [solicitudes, setSolicitudes] = useState<Solicitud[]>(INITIAL_SOLICITUDES);
  const [activeTab, setActiveTab] = useState<string>('todas');
  const [feedbackMsg, setFeedbackMsg] = useState<{ text: string; error?: boolean } | null>(null);
  
  // Modals state
  const [modalNewOpen, setModalNewOpen] = useState(false);
  const [modalScheduleOpen, setModalScheduleOpen] = useState<string | null>(null);
  const [scheduleDate, setScheduleDate] = useState('');
  const [schedulePrice, setSchedulePrice] = useState(3000000);

  // New Solicitud Form
  const [newTitle, setNewTitle] = useState('');
  const [newDesc, setNewDesc] = useState('');
  const [newPrice, setNewPrice] = useState(1200000);
  const [newWorkerId, setNewWorkerId] = useState('usr-trabajador-1');

  const actorId = currentUser?.id ?? 'usr-cliente-1';

  const applyStateTransition = (solId: string, actionFn: (machine: SolicitudStateMachine) => void) => {
    try {
      const target = solicitudes.find(s => s.id === solId);
      if (!target) return;

      const machine = new SolicitudStateMachine(target, (updated) => {
        setSolicitudes(prev => prev.map(s => s.id === updated.id ? updated : s));
      });

      actionFn(machine);
      setFeedbackMsg({ text: `Estado actualizado exitosamente a: ${machine.getStateName()}` });
    } catch (err: any) {
      setFeedbackMsg({ text: err.message || 'Error en la transición de estado.', error: true });
    }
  };

  const handleCreateSolicitud = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newTitle || !newDesc) return;

    const nueva: Solicitud = {
      id: 'sol-' + Date.now().toString().slice(-4),
      clienteId: actorId,
      clienteNombre: currentUser?.name ?? 'Camila Ramos',
      clienteAvatar: currentUser?.avatar,
      trabajadorId: newWorkerId,
      trabajadorNombre: newWorkerId === 'usr-trabajador-1' ? 'Camilo Restrepo' : 'Valentina Gómez',
      trabajadorAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
      trabajadorProfesion: newWorkerId === 'usr-trabajador-1' ? 'Desarrollador Fullstack' : 'Diseñadora UX/UI',
      titulo: newTitle,
      descripcion: newDesc,
      estado: 'PENDIENTE',
      valorPropuesto: newPrice,
      createdAt: 'Justo ahora'
    };

    setSolicitudes(prev => [nueva, ...prev]);
    setModalNewOpen(false);
    setNewTitle('');
    setNewDesc('');
    setFeedbackMsg({ text: `¡Solicitud '${nueva.titulo}' creada exitosamente en estado PENDIENTE!` });
  };

  const filteredSolicitudes = solicitudes.filter(s => {
    if (activeTab === 'todas') return true;
    return s.estado === activeTab;
  });

  const getBadgeColor = (estado: EstadoSolicitudEnum) => {
    switch (estado) {
      case 'PENDIENTE': return 'bg-[#FEFAF0] text-[#8C7738] border-[#FDF1B8]';
      case 'ACEPTADA': return 'bg-[#EBF3F8] text-[#3A6B88] border-[#CFE2EE]';
      case 'PROGRAMADA': return 'bg-[#F2F4FB] text-[#655B8B] border-[#DCD4F0]';
      case 'EN_PROCESO': return 'bg-[#FDF5F2] text-[#A25844] border-[#F9D6C9]';
      case 'FINALIZADA': return 'bg-[#EAF4F4] text-[#537A72] border-[#CCE3DE]';
      case 'RECHAZADA':
      case 'CANCELADA': return 'bg-[#FFF0F0] text-[#B94A48] border-[#F5C6CB]';
    }
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-body">
      
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 mb-6 border-b border-[#E8E2D8]">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE]">
              <span className="w-2 h-2 rounded-full bg-[#6B9080]"></span>
              State Pattern (05_PATRONES)
            </span>
            <span className="text-xs text-[#8C847C]">Ciclo de vida y transición segura de estados</span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-bold font-headline text-[#2C2724]">Gestor de Solicitudes</h1>
        </div>

        <button
          onClick={() => setModalNewOpen(true)}
          className="py-2.5 px-5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-2 self-start md:self-auto"
        >
          <span className="material-symbols-outlined text-[17px]">add_task</span>
          <span>Nueva Solicitud</span>
        </button>
      </div>

      {/* Feedback Message */}
      {feedbackMsg && (
        <div
          className={`mb-5 p-3.5 rounded-2xl text-xs font-semibold flex items-center justify-between border ${
            feedbackMsg.error
              ? 'bg-[#FFF0F0] border-[#F5C6CB] text-[#B94A48]'
              : 'bg-[#EAF4F4] border-[#CCE3DE] text-[#537A72]'
          }`}
        >
          <span>{feedbackMsg.text}</span>
          <button onClick={() => setFeedbackMsg(null)} className="text-xs hover:opacity-75">✕</button>
        </div>
      )}

      {/* Tabs Filter */}
      <div className="flex items-center gap-2 overflow-x-auto pb-3 mb-6 text-xs font-semibold">
        {[
          { key: 'todas', label: 'Todas las Solicitudes' },
          { key: 'PENDIENTE', label: 'Pendientes' },
          { key: 'ACEPTADA', label: 'Aceptadas' },
          { key: 'PROGRAMADA', label: 'Programadas' },
          { key: 'EN_PROCESO', label: 'En Ejecución' },
          { key: 'FINALIZADA', label: 'Finalizadas' },
          { key: 'CANCELADA', label: 'Canceladas' }
        ].map(t => (
          <button
            key={t.key}
            onClick={() => setActiveTab(t.key)}
            className={`px-3.5 py-1.5 rounded-xl whitespace-nowrap transition-all ${
              activeTab === t.key
                ? 'bg-[#6B9080] text-white font-bold shadow-sm'
                : 'bg-white text-[#7A726A] border border-[#E8E2D8] hover:border-[#CCE3DE]'
            }`}
          >
            {t.label}
          </button>
        ))}
      </div>

      {/* Cards List */}
      <div className="grid grid-cols-1 gap-4">
        {filteredSolicitudes.map(sol => (
          <div key={sol.id} className="p-6 rounded-3xl bg-white border border-[#E8E2D8] shadow-sm hover:shadow-md transition-all">
            
            {/* Top Bar */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 mb-3 border-b border-[#F0EAE1]">
              <div className="flex items-center gap-2.5">
                <span className={`text-xs font-bold px-3 py-1 rounded-full border ${getBadgeColor(sol.estado)}`}>
                  ● Estado: {sol.estado}
                </span>
                <span className="text-xs text-[#8C847C] font-mono">ID: {sol.id}</span>
              </div>
              <div className="text-xs">
                <span className="text-[#8C847C]">Presupuesto: </span>
                <span className="font-bold text-[#537A72]">
                  ${sol.valorAcordado ? sol.valorAcordado.toLocaleString('es-CO') : sol.valorPropuesto.toLocaleString('es-CO')} COP
                </span>
              </div>
            </div>

            {/* Title & Desc */}
            <h3 className="text-base sm:text-lg font-bold font-headline text-[#2C2724] mb-1">{sol.titulo}</h3>
            <p className="text-xs text-[#56504A] leading-relaxed mb-4">{sol.descripcion}</p>

            {/* Parties Info */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 rounded-2xl bg-[#FBF9F5] border border-[#E8E2D8] text-xs mb-4">
              <div>
                <span className="text-[10px] text-[#8C847C] block">Cliente:</span>
                <span className="font-bold text-[#2C2724]">{sol.clienteNombre}</span>
              </div>
              <div>
                <span className="text-[10px] text-[#8C847C] block">Camellador:</span>
                <span className="font-bold text-[#2C2724]">{sol.trabajadorNombre}</span>
              </div>
              <div>
                <span className="text-[10px] text-[#8C847C] block">Fecha Programada:</span>
                <span className="font-bold text-[#2C2724]">{sol.fechaProgramada || 'Sin programar'}</span>
              </div>
            </div>

            {sol.motivoCancelacion && (
              <div className="mb-4 p-2.5 rounded-xl bg-[#FFF0F0] border border-[#F5C6CB] text-xs text-[#B94A48]">
                <strong>Motivo:</strong> {sol.motivoCancelacion}
              </div>
            )}

            {/* State Pattern Transition Actions */}
            <div className="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-[#F0EAE1]">
              <button
                onClick={() => {
                  onOpenChatWith(sol.trabajadorId);
                  setScreen('messages');
                }}
                className="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#F2F4FB] text-[#655B8B] border border-[#DCD4F0] text-xs font-semibold hover:bg-[#E5E9F7] transition-all"
              >
                <span className="material-symbols-outlined text-[16px]">chat</span>
                <span>Chat con {sol.trabajadorNombre}</span>
              </button>

              <div className="flex items-center gap-2">
                {sol.estado === 'PENDIENTE' && (
                  <>
                    <button
                      onClick={() => applyStateTransition(sol.id, (m) => m.aceptar(actorId))}
                      className="px-3.5 py-1.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all"
                    >
                      Aceptar
                    </button>
                    <button
                      onClick={() => applyStateTransition(sol.id, (m) => m.rechazar(actorId, 'Horario no disponible'))}
                      className="px-3.5 py-1.5 rounded-xl bg-[#FFF0F0] text-[#B94A48] border border-[#F5C6CB] text-xs font-bold"
                    >
                      Rechazar
                    </button>
                  </>
                )}

                {sol.estado === 'ACEPTADA' && (
                  <>
                    <button
                      onClick={() => setModalScheduleOpen(sol.id)}
                      className="px-3.5 py-1.5 rounded-xl bg-[#655B8B] hover:bg-[#524872] text-white text-xs font-bold shadow-sm"
                    >
                      Programar Cita
                    </button>
                    <button
                      onClick={() => applyStateTransition(sol.id, (m) => m.cancelar(actorId, 'Cancelada por cliente'))}
                      className="text-xs text-[#B94A48] hover:underline"
                    >
                      Cancelar
                    </button>
                  </>
                )}

                {sol.estado === 'PROGRAMADA' && (
                  <>
                    <button
                      onClick={() => applyStateTransition(sol.id, (m) => m.iniciar(actorId))}
                      className="px-3.5 py-1.5 rounded-xl bg-[#E79E78] hover:bg-[#D58C66] text-white text-xs font-bold shadow-sm"
                    >
                      Iniciar Camello
                    </button>
                    <button
                      onClick={() => applyStateTransition(sol.id, (m) => m.cancelar(actorId, 'Cancelada en agenda'))}
                      className="text-xs text-[#B94A48] hover:underline"
                    >
                      Cancelar
                    </button>
                  </>
                )}

                {sol.estado === 'EN_PROCESO' && (
                  <button
                    onClick={() => applyStateTransition(sol.id, (m) => m.finalizar(actorId))}
                    className="px-4 py-1.5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm"
                  >
                    Finalizar y Entregar
                  </button>
                )}

                {sol.estado === 'FINALIZADA' && (
                  <span className="text-xs font-bold text-[#6B9080] flex items-center gap-1">
                    <span className="material-symbols-outlined text-[16px]">check_circle</span>
                    <span>Camello Completado con Éxito</span>
                  </span>
                )}
              </div>
            </div>

          </div>
        ))}
      </div>

      {/* Modal Programar Cita */}
      {modalScheduleOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-[#E8E2D8]">
            <h3 className="font-bold font-headline text-base text-[#2C2724] mb-3">Agendar Fecha de Servicio</h3>
            <div className="space-y-3 text-xs">
              <div>
                <label className="block font-bold text-[#2C2724] mb-1">Fecha y Hora</label>
                <input
                  type="datetime-local"
                  value={scheduleDate}
                  onChange={(e) => setScheduleDate(e.target.value)}
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                />
              </div>
              <div>
                <label className="block font-bold text-[#2C2724] mb-1">Valor Acordado (COP)</label>
                <input
                  type="number"
                  value={schedulePrice}
                  onChange={(e) => setSchedulePrice(Number(e.target.value))}
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                />
              </div>
              <div className="pt-2 flex gap-2">
                <button
                  onClick={() => {
                    applyStateTransition(modalScheduleOpen, (m) => m.programar(actorId, scheduleDate || '2026-10-25 10:00', schedulePrice));
                    setModalScheduleOpen(null);
                  }}
                  className="flex-1 py-2 rounded-xl bg-[#655B8B] text-white font-bold"
                >
                  Confirmar Agenda
                </button>
                <button
                  onClick={() => setModalScheduleOpen(null)}
                  className="px-3 py-2 rounded-xl bg-[#F6F3EC]"
                >
                  Cancelar
                </button>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Modal Nueva Solicitud */}
      {modalNewOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#E8E2D8]">
            <div className="flex items-center justify-between pb-3 border-b border-[#F0EAE1] mb-4">
              <h3 className="font-bold font-headline text-base text-[#2C2724]">Crear Nueva Solicitud</h3>
              <button onClick={() => setModalNewOpen(false)} className="text-[#8C847C]">✕</button>
            </div>
            <form onSubmit={handleCreateSolicitud} className="space-y-3 text-xs">
              <div>
                <label className="block font-bold mb-1">Camellador Destinatario</label>
                <select
                  value={newWorkerId}
                  onChange={(e) => setNewWorkerId(e.target.value)}
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                >
                  <option value="usr-trabajador-1">Camilo Restrepo (Fullstack Cloud ⭐ 4.95)</option>
                  <option value="usr-trabajador-2">Valentina Gómez (Diseño UX/UI ⭐ 4.90)</option>
                </select>
              </div>
              <div>
                <label className="block font-bold mb-1">Título del Trabajo</label>
                <input
                  type="text"
                  required
                  value={newTitle}
                  onChange={(e) => setNewTitle(e.target.value)}
                  placeholder="Ej. Integración de Pasarela de Pagos"
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                />
              </div>
              <div>
                <label className="block font-bold mb-1">Descripción</label>
                <textarea
                  required
                  rows={3}
                  value={newDesc}
                  onChange={(e) => setNewDesc(e.target.value)}
                  placeholder="Requisitos y entregables..."
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                />
              </div>
              <div>
                <label className="block font-bold mb-1">Presupuesto Propuesto (COP)</label>
                <input
                  type="number"
                  value={newPrice}
                  onChange={(e) => setNewPrice(Number(e.target.value))}
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8]"
                />
              </div>
              <div className="pt-2 flex gap-2">
                <button type="submit" className="flex-1 py-2.5 rounded-xl bg-[#6B9080] text-white font-bold shadow-sm">
                  Enviar Solicitud
                </button>
                <button type="button" onClick={() => setModalNewOpen(false)} className="px-3 py-2.5 rounded-xl bg-[#F6F3EC]">
                  Cerrar
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

    </div>
  );
};
