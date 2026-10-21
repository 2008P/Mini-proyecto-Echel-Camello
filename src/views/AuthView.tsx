import React, { useState } from 'react';
import { User, UserRole, AppScreen } from '../types';

interface AuthViewProps {
  initialMode?: 'login' | 'register' | 'recover';
  onLoginSuccess: (user: User) => void;
  setScreen: (screen: AppScreen) => void;
}

export const AuthView: React.FC<AuthViewProps> = ({
  initialMode = 'login',
  onLoginSuccess,
  setScreen,
}) => {
  const [mode, setMode] = useState<'login' | 'register' | 'recover'>(initialMode);
  
  // Login form
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  
  // Register form
  const [regName, setRegName] = useState('');
  const [regEmail, setRegEmail] = useState('');
  const [regPassword, setRegPassword] = useState('');
  const [regRole, setRegRole] = useState<UserRole>('CLIENTE');
  const [regProfession, setRegProfession] = useState('');
  const [regPhone, setRegPhone] = useState('');
  
  // Alert message
  const [alertMsg, setAlertMsg] = useState<{ type: 'error' | 'success' | 'info'; text: string } | null>(null);
  const [simulatedMail, setSimulatedMail] = useState<{ to: string; subject: string; actionText: string; onClick: () => void } | null>(null);

  const handleLoginSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email || !password) {
      setAlertMsg({ type: 'error', text: 'Por favor completa todos los campos requeridos.' });
      return;
    }

    // Usuario demo autenticado
    const loggedUser: User = {
      id: 'usr-demo-' + Date.now(),
      name: email.split('@')[0].toUpperCase(),
      email: email,
      role: 'CLIENTE',
      status: 'ACTIVO',
      avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
      profession: 'Profesional en Contratación'
    };

    onLoginSuccess(loggedUser);
    setScreen('requests');
  };

  const handleQuickRoleLogin = (role: UserRole, name: string, userEmail: string, avatar: string, profession: string) => {
    const user: User = {
      id: 'usr-' + role.toLowerCase() + '-1',
      name,
      email: userEmail,
      role,
      status: 'ACTIVO',
      avatar,
      profession
    };
    onLoginSuccess(user);
    setScreen('requests');
  };

  const handleRegisterSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!regName || !regEmail || !regPassword) {
      setAlertMsg({ type: 'error', text: 'Todos los campos obligatorios deben ser diligenciados.' });
      return;
    }

    // Regla 05_PATRONES: no auto-asignar ADMINISTRADOR
    const effectiveRole: UserRole = regRole === 'ADMINISTRADOR' ? 'CLIENTE' : regRole;

    // Simulación Adapter Correo SMTP
    setSimulatedMail({
      to: regEmail,
      subject: 'Verifica tu cuenta en Échele Camello',
      actionText: 'Activar Cuenta de Inmediato (1 Clic)',
      onClick: () => {
        const newUser: User = {
          id: 'usr-' + Date.now(),
          name: regName,
          email: regEmail,
          role: effectiveRole,
          status: 'ACTIVO',
          profession: regProfession || 'Camellador Profesional',
          avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80'
        };
        onLoginSuccess(newUser);
        setSimulatedMail(null);
        setScreen('requests');
      }
    });

    setAlertMsg({
      type: 'success',
      text: `¡Cuenta creada como ${effectiveRole}! Hemos enviado un correo de activación vía Adapter SMTP.`
    });
  };

  const handleRecoverSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email) {
      setAlertMsg({ type: 'error', text: 'Escribe tu correo registrado.' });
      return;
    }

    setSimulatedMail({
      to: email,
      subject: 'Restablecer contraseña en Échele Camello',
      actionText: 'Restablecer Contraseña (Simulación)',
      onClick: () => {
        setAlertMsg({ type: 'success', text: 'Tu contraseña ha sido actualizada exitosamente. Ya puedes iniciar sesión.' });
        setSimulatedMail(null);
        setMode('login');
      }
    });

    setAlertMsg({
      type: 'info',
      text: 'Se ha generado un token seguro y enviado las instrucciones a tu correo.'
    });
  };

  return (
    <div className="max-w-xl mx-auto px-4 py-12">
      <div className="bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E2D8] shadow-[0_12px_40px_rgba(44,39,36,0.05)]">
        
        {/* Toggle Mode Pills */}
        <div className="flex items-center justify-center gap-1.5 p-1 rounded-2xl bg-[#F6F3EC] mb-6">
          <button
            onClick={() => { setMode('login'); setAlertMsg(null); }}
            className={`flex-1 py-2 text-xs font-bold rounded-xl transition-all ${
              mode === 'login' ? 'bg-white text-[#2C2724] shadow-sm' : 'text-[#7A726A] hover:text-[#2C2724]'
            }`}
          >
            Iniciar Sesión
          </button>
          <button
            onClick={() => { setMode('register'); setAlertMsg(null); }}
            className={`flex-1 py-2 text-xs font-bold rounded-xl transition-all ${
              mode === 'register' ? 'bg-white text-[#2C2724] shadow-sm' : 'text-[#7A726A] hover:text-[#2C2724]'
            }`}
          >
            Crear Cuenta
          </button>
          <button
            onClick={() => { setMode('recover'); setAlertMsg(null); }}
            className={`flex-1 py-2 text-xs font-bold rounded-xl transition-all ${
              mode === 'recover' ? 'bg-white text-[#2C2724] shadow-sm' : 'text-[#7A726A] hover:text-[#2C2724]'
            }`}
          >
            Recuperar Clave
          </button>
        </div>

        {/* Header */}
        <div className="text-center mb-6">
          <span className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#EAF4F4] text-[#537A72] border border-[#CCE3DE] mb-2">
            <span className="material-symbols-outlined text-[15px]">security</span>
            Arquitectura de Roles & Acceso
          </span>
          <h2 className="text-2xl sm:text-3xl font-bold font-headline text-[#2C2724]">
            {mode === 'login' && 'Bienvenido a Échele Camello'}
            {mode === 'register' && 'Regístrate en la Plataforma'}
            {mode === 'recover' && 'Recuperación de Contraseña'}
          </h2>
          <p className="text-xs sm:text-sm text-[#7A726A] mt-1">
            {mode === 'login' && 'Accede con tu cuenta o selecciona un rol de prueba instantáneo.'}
            {mode === 'register' && 'Elige si buscas contratar o prestar tus servicios profesionales.'}
            {mode === 'recover' && 'Te enviaremos un token seguro a tu correo electrónico registrado.'}
          </p>
        </div>

        {/* Alert Msg */}
        {alertMsg && (
          <div
            className={`mb-5 p-3.5 rounded-2xl text-xs font-semibold flex items-center gap-2 border ${
              alertMsg.type === 'error'
                ? 'bg-[#FFF0F0] border-[#F5C6CB] text-[#B94A48]'
                : alertMsg.type === 'success'
                ? 'bg-[#EAF4F4] border-[#CCE3DE] text-[#537A72]'
                : 'bg-[#F2F4FB] border-[#DCD4F0] text-[#655B8B]'
            }`}
          >
            <span className="material-symbols-outlined text-[17px]">info</span>
            <span>{alertMsg.text}</span>
          </div>
        )}

        {/* Adapter Correo Simulado Card */}
        {simulatedMail && (
          <div className="mb-6 p-4 rounded-2xl bg-[#FBF9F5] border border-[#CCE3DE] text-left">
            <div className="flex items-center justify-between pb-2 mb-2 border-b border-[#E8E2D8]">
              <span class="text-xs font-bold text-[#537A72] flex items-center gap-1">
                <span className="material-symbols-outlined text-[16px]">outgoing_mail</span>
                Adapter de Correo SMTP Activo
              </span>
              <span className="text-[10px] text-[#8C847C]">Simulación Local</span>
            </div>
            <p className="text-xs text-[#56504A] mb-2">
              Para: <strong>{simulatedMail.to}</strong><br />
              Asunto: <em>{simulatedMail.subject}</em>
            </p>
            <button
              onClick={simulatedMail.onClick}
              className="w-full py-2 px-3 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5"
            >
              <span className="material-symbols-outlined text-[16px]">verified</span>
              <span>{simulatedMail.actionText}</span>
            </button>
          </div>
        )}

        {/* FORMS */}
        {mode === 'login' && (
          <form onSubmit={handleLoginSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Correo Electrónico</label>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="ejemplo@echelecamello.co"
                className="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080]"
              />
            </div>
            <div>
              <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Contraseña</label>
              <input
                type="password"
                required
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                className="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080]"
              />
            </div>
            <button
              type="submit"
              className="w-full py-3 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all"
            >
              Iniciar Sesión
            </button>
          </form>
        )}

        {mode === 'register' && (
          <form onSubmit={handleRegisterSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1.5">Rol Principal</label>
              <div className="grid grid-cols-2 gap-2">
                <button
                  type="button"
                  onClick={() => setRegRole('CLIENTE')}
                  className={`p-3 rounded-xl border text-left transition-all ${
                    regRole === 'CLIENTE'
                      ? 'border-[#6B9080] bg-[#EAF4F4] text-[#2C2724]'
                      : 'border-[#E8E2D8] bg-white text-[#7A726A]'
                  }`}
                >
                  <div className="font-bold text-xs">Cliente / Empleador</div>
                  <div className="text-[10px] text-[#7A726A]">Solicito y contrato camellos</div>
                </button>
                <button
                  type="button"
                  onClick={() => setRegRole('TRABAJADOR')}
                  className={`p-3 rounded-xl border text-left transition-all ${
                    regRole === 'TRABAJADOR'
                      ? 'border-[#6B9080] bg-[#EAF4F4] text-[#2C2724]'
                      : 'border-[#E8E2D8] bg-white text-[#7A726A]'
                  }`}
                >
                  <div className="font-bold text-xs">Camellador / Profesional</div>
                  <div className="text-[10px] text-[#7A726A]">Ofrezco mis servicios</div>
                </button>
              </div>
            </div>

            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Nombre Completo</label>
                <input
                  type="text"
                  required
                  value={regName}
                  onChange={(e) => setRegName(e.target.value)}
                  placeholder="Mateo Gómez"
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Correo Electrónico</label>
                <input
                  type="email"
                  required
                  value={regEmail}
                  onChange={(e) => setRegEmail(e.target.value)}
                  placeholder="mateo@empresa.com"
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
                />
              </div>
            </div>

            <div className="grid grid-cols-2 gap-3">
              <div>
                <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Profesión / Especialidad</label>
                <input
                  type="text"
                  value={regProfession}
                  onChange={(e) => setRegProfession(e.target.value)}
                  placeholder="Ej. Diseñador UI/UX"
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Teléfono</label>
                <input
                  type="tel"
                  value={regPhone}
                  onChange={(e) => setRegPhone(e.target.value)}
                  placeholder="+57 312 000 0000"
                  className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
                />
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Contraseña</label>
              <input
                type="password"
                required
                value={regPassword}
                onChange={(e) => setRegPassword(e.target.value)}
                placeholder="Mínimo 6 caracteres"
                className="w-full py-2 px-3 rounded-xl border border-[#E8E2D8] text-xs"
              />
            </div>

            <button
              type="submit"
              className="w-full py-3 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all"
            >
              Registrar Cuenta
            </button>
          </form>
        )}

        {mode === 'recover' && (
          <form onSubmit={handleRecoverSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-[#2C2724] uppercase mb-1">Correo Electrónico Registrado</label>
              <input
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="ejemplo@echelecamello.co"
                className="w-full py-2.5 px-3.5 rounded-xl border border-[#E8E2D8] text-xs focus:border-[#6B9080]"
              />
            </div>
            <button
              type="submit"
              className="w-full py-3 rounded-xl bg-[#6B9080] hover:bg-[#5A7C6E] text-white text-xs font-bold shadow-sm transition-all"
            >
              Enviar Enlace de Recuperación
            </button>
          </form>
        )}

        {/* Acceso Rápido por Roles para Evaluación */}
        <div className="mt-8 pt-5 border-t border-[#F0EAE1] bg-[#FBF9F5] -mx-8 -mb-8 p-5 rounded-b-3xl">
          <p className="text-[11px] font-bold text-[#2C2724] uppercase tracking-wider mb-2 flex items-center gap-1.5">
            <span className="material-symbols-outlined text-[15px] text-[#6B9080]">touch_app</span>
            Acceso Rápido por Roles (1 Clic)
          </p>
          <div className="grid grid-cols-2 gap-2 text-left">
            <button
              type="button"
              onClick={() => handleQuickRoleLogin('CLIENTE', 'Camila Ramos', 'camila.ramos@empresa.com', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80', 'Gerente de Contratación')}
              className="p-2 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] flex items-center gap-2"
            >
              <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=100&q=80" class="w-7 h-7 rounded-lg object-cover" />
              <div>
                <div className="text-[11px] font-bold text-[#2C2724]">Camila (Cliente)</div>
                <div className="text-[9px] text-[#A25844]">Rol: CLIENTE</div>
              </div>
            </button>

            <button
              type="button"
              onClick={() => handleQuickRoleLogin('TRABAJADOR', 'Camilo Restrepo', 'camilo.restrepo@echelecamello.co', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80', 'Desarrollador Fullstack Cloud')}
              className="p-2 rounded-xl bg-white border border-[#E8E2D8] hover:border-[#6B9080] flex items-center gap-2"
            >
              <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="w-7 h-7 rounded-lg object-cover" />
              <div>
                <div className="text-[11px] font-bold text-[#2C2724]">Camilo (Trabajador)</div>
                <div className="text-[9px] text-[#537A72]">Rol: TRABAJADOR</div>
              </div>
            </button>
          </div>
        </div>

      </div>
    </div>
  );
};
