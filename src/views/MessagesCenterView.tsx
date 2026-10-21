import React, { useState } from 'react';
import { User, Conversacion, Mensaje } from '../types';

interface MessagesCenterViewProps {
  currentUser: User | null;
  selectedWorkerId?: string;
}

const INITIAL_CONVERSACIONES: Conversacion[] = [
  {
    id: 'conv-001',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-1',
    trabajadorNombre: 'Camilo Restrepo',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
    ultimoMensaje: '¡Excelente! Quedamos confirmados para el kickoff el sábado a las 9:30 AM.',
    ultimoMensajeAt: '11:45 AM',
    noLeidos: 0
  },
  {
    id: 'conv-002',
    clienteId: 'usr-cliente-1',
    clienteNombre: 'Camila Ramos',
    clienteAvatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    trabajadorId: 'usr-trabajador-2',
    trabajadorNombre: 'Valentina Gómez',
    trabajadorAvatar: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=150&q=80',
    ultimoMensaje: 'Te he compartido el enlace del prototipo en Figma con los comentarios resueltos.',
    ultimoMensajeAt: '12:10 PM',
    noLeidos: 1
  }
];

const INITIAL_MENSAJES: Record<string, Mensaje[]> = {
  'conv-001': [
    {
      id: 'm-1',
      conversacionId: 'conv-001',
      remitenteId: 'usr-cliente-1',
      contenido: 'Hola Camilo, un gusto saludarte. Vimos tu portafolio y nos interesa avanzar con el módulo de pagos.',
      leido: true,
      createdAt: '10:00 AM'
    },
    {
      id: 'm-2',
      conversacionId: 'conv-001',
      remitenteId: 'usr-trabajador-1',
      contenido: '¡Hola Camila! Muchas gracias por escribirme. Con gusto podemos agendar una sesión inicial para revisar los endpoints de Wompi.',
      leido: true,
      createdAt: '10:15 AM'
    },
    {
      id: 'm-3',
      conversacionId: 'conv-001',
      remitenteId: 'usr-cliente-1',
      contenido: 'Perfecto. Acabo de aprobar la propuesta económica por $3.500.000 COP y programé la fecha.',
      leido: true,
      createdAt: '11:30 AM'
    },
    {
      id: 'm-4',
      conversacionId: 'conv-001',
      remitenteId: 'usr-trabajador-1',
      contenido: '¡Excelente! Quedamos confirmados para el kickoff el sábado a las 9:30 AM.',
      leido: true,
      createdAt: '11:45 AM'
    }
  ],
  'conv-002': [
    {
      id: 'm-201',
      conversacionId: 'conv-002',
      remitenteId: 'usr-trabajador-2',
      contenido: 'Hola Camila, acabo de subir los wireframes de alta fidelidad para el flujo de checkout.',
      adjuntoUrl: 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=400&q=80',
      adjuntoTipo: 'image',
      leido: true,
      createdAt: '11:00 AM'
    },
    {
      id: 'm-202',
      conversacionId: 'conv-002',
      remitenteId: 'usr-cliente-1',
      contenido: '¡Quedaron preciosos! Me encanta la suavidad de los tonos pastel y la claridad de los botones.',
      leido: true,
      createdAt: '11:50 AM'
    },
    {
      id: 'm-203',
      conversacionId: 'conv-002',
      remitenteId: 'usr-trabajador-2',
      contenido: 'Te he compartido el enlace del prototipo en Figma con los comentarios resueltos.',
      leido: false,
      createdAt: '12:10 PM'
    }
  ]
};

export const MessagesCenterView: React.FC<MessagesCenterViewProps> = ({
  currentUser,
  selectedWorkerId,
}) => {
  const [conversaciones, setConversaciones] = useState<Conversacion[]>(INITIAL_CONVERSACIONES);
  const [activeConvId, setActiveConvId] = useState<string>(
    selectedWorkerId === 'usr-trabajador-2' ? 'conv-002' : 'conv-001'
  );
  const [mensajesState, setMensajesState] = useState<Record<string, Mensaje[]>>(INITIAL_MENSAJES);
  const [inputText, setInputText] = useState('');
  const [attachedImage, setAttachedImage] = useState<string | null>(null);

  const actorId = currentUser?.id ?? 'usr-cliente-1';
  const activeConv = conversaciones.find(c => c.id === activeConvId) ?? conversaciones[0];
  const activeMensajes = mensajesState[activeConvId] || [];

  const handleSendMessage = (e: React.FormEvent) => {
    e.preventDefault();
    if (!inputText && !attachedImage) return;

    const newMsg: Mensaje = {
      id: 'm-' + Date.now(),
      conversacionId: activeConvId,
      remitenteId: actorId,
      contenido: inputText,
      adjuntoUrl: attachedImage ?? undefined,
      adjuntoTipo: attachedImage ? 'image' : undefined,
      leido: false,
      createdAt: 'Justo ahora'
    };

    setMensajesState(prev => ({
      ...prev,
      [activeConvId]: [...(prev[activeConvId] || []), newMsg]
    }));

    // Actualizar último mensaje en la conversación
    setConversaciones(prev => prev.map(c => {
      if (c.id === activeConvId) {
        return {
          ...c,
          ultimoMensaje: inputText || 'Archivo adjunto',
          ultimoMensajeAt: 'Justo ahora',
          noLeidos: 0
        };
      }
      return c;
    }));

    setInputText('');
    setAttachedImage(null);
  };

  const handleSimulateAttachment = () => {
    // Demostración del Adapter de Archivos
    setAttachedImage('https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=400&q=80');
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-body">
      
      <div className="flex items-center justify-between pb-6 mb-6 border-b border-[#E8E2D8]">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#F2F4FB] text-[#655B8B] border border-[#DCD4F0]">
              <span class="material-symbols-outlined text-[15px]">forum</span>
              Módulo de Comunicación (RF-41 a 46)
            </span>
            <span className="text-xs text-[#8C847C]">Repositorio y Adapter de Almacenamiento</span>
          </div>
          <h1 className="text-2xl sm:text-3xl font-bold font-headline text-[#2C2724]">Bandeja de Mensajería</h1>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-white rounded-3xl border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.04)] overflow-hidden min-h-[560px]">
        
        {/* Left Column: Conversations */}
        <div className="lg:col-span-4 border-r border-[#E8E2D8] flex flex-col bg-[#FDFBF9]">
          <div className="p-4 border-b border-[#E8E2D8]">
            <input
              type="text"
              placeholder="Buscar en mensajes..."
              className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
            />
          </div>

          <div className="overflow-y-auto flex-grow divide-y divide-[#F0EAE1]">
            {conversaciones.map(c => {
              const isActive = c.id === activeConvId;
              return (
                <div
                  key={c.id}
                  onClick={() => setActiveConvId(c.id)}
                  className={`p-4 cursor-pointer transition-all ${
                    isActive ? 'bg-[#EAF4F4]/70 border-l-4 border-[#6B9080]' : 'hover:bg-[#F6F3EC]/60'
                  }`}
                >
                  <div className="flex items-center gap-3">
                    <img
                      src={c.trabajadorAvatar}
                      alt={c.trabajadorNombre}
                      className="w-10 h-10 rounded-xl object-cover shrink-0 border border-[#E8E2D8]"
                    />
                    <div className="flex-grow min-w-0">
                      <div className="flex items-center justify-between mb-0.5">
                        <span className="font-bold text-xs text-[#2C2724] truncate">{c.trabajadorNombre}</span>
                        <span className="text-[10px] text-[#8C847C]">{c.ultimoMensajeAt}</span>
                      </div>
                      <p className="text-xs text-[#7A726A] truncate">{c.ultimoMensaje}</p>
                    </div>
                    {c.noLeidos && c.noLeidos > 0 ? (
                      <span className="w-5 h-5 rounded-full bg-[#E79E78] text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                        {c.noLeidos}
                      </span>
                    ) : null}
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Right Column: Chat Stream */}
        <div className="lg:col-span-8 flex flex-col justify-between bg-white min-h-[500px]">
          {activeConv ? (
            <>
              {/* Chat Header */}
              <div className="p-4 px-6 border-b border-[#E8E2D8] flex items-center justify-between bg-white">
                <div className="flex items-center gap-3">
                  <img
                    src={activeConv.trabajadorAvatar}
                    alt={activeConv.trabajadorNombre}
                    className="w-10 h-10 rounded-xl object-cover border border-[#E8E2D8]"
                  />
                  <div>
                    <h3 className="font-bold font-headline text-sm text-[#2C2724]">{activeConv.trabajadorNombre}</h3>
                    <span className="text-[11px] text-[#537A72] flex items-center gap-1 font-semibold">
                      <span className="w-1.5 h-1.5 rounded-full bg-[#6B9080]"></span> En línea · Verificado
                    </span>
                  </div>
                </div>
              </div>

              {/* Messages Body */}
              <div className="p-6 overflow-y-auto flex-grow space-y-3 max-h-[400px] bg-[#FDFBF9]">
                {activeMensajes.map(m => {
                  const isMine = m.remitenteId === actorId;
                  return (
                    <div key={m.id} className={`flex flex-col ${isMine ? 'items-end' : 'items-start'}`}>
                      <div
                        className={`max-w-[78%] rounded-2xl p-3.5 text-xs shadow-sm ${
                          isMine
                            ? 'bg-[#6B9080] text-white rounded-tr-none'
                            : 'bg-white text-[#2C2724] border border-[#E8E2D8] rounded-tl-none'
                        }`}
                      >
                        {m.adjuntoUrl && (
                          <div className="mb-2">
                            <img
                              src={m.adjuntoUrl}
                              alt="Adjunto"
                              className="max-h-48 rounded-xl object-cover border border-white/20"
                            />
                          </div>
                        )}
                        <p className="leading-relaxed">{m.contenido}</p>
                        <div className={`flex items-center justify-end gap-1 mt-1 text-[9px] ${isMine ? 'text-white/80' : 'text-[#8C847C]'}`}>
                          <span>{m.createdAt}</span>
                          {isMine && <span className="material-symbols-outlined text-[12px]">done_all</span>}
                        </div>
                      </div>
                    </div>
                  );
                })}
              </div>

              {/* Chat Input */}
              <form onSubmit={handleSendMessage} className="p-4 border-t border-[#E8E2D8] bg-white flex items-center gap-2">
                <button
                  type="button"
                  onClick={handleSimulateAttachment}
                  title="Simular adjunto con Adapter de Archivos"
                  className="p-2.5 rounded-xl border border-[#E8E2D8] hover:bg-[#F6F3EC] text-[#537A72] flex items-center justify-center transition-all"
                >
                  <span className="material-symbols-outlined text-[18px]">attachment</span>
                </button>

                {attachedImage && (
                  <span className="text-[10px] font-bold text-[#6B9080] bg-[#EAF4F4] px-2 py-1 rounded-md flex items-center gap-1">
                    Imagen lista
                    <button type="button" onClick={() => setAttachedImage(null)} className="ml-1 text-red-500">✕</button>
                  </span>
                )}

                <input
                  type="text"
                  value={inputText}
                  onChange={(e) => setInputText(e.target.value)}
                  placeholder="Escribe tu mensaje aquí..."
                  className="flex-grow py-2.5 px-4 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080]"
                />

                <button
                  type="submit"
                  className="py-2.5 px-5 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5"
                >
                  <span>Enviar</span>
                  <span className="material-symbols-outlined text-[16px]">send</span>
                </button>
              </form>
            </>
          ) : (
            <div className="flex items-center justify-center h-full text-xs text-[#8C847C]">
              Selecciona una conversación para chatear.
            </div>
          )}
        </div>

      </div>

    </div>
  );
};
