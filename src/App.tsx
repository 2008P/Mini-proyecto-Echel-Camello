/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

import React, { useState, useEffect } from 'react';
import { AppScreen, User } from './types';
import { Navbar } from './components/Navbar';
import { Footer } from './components/Footer';
import { HomeView } from './views/HomeView';
import { JobsExplorerView } from './views/JobsExplorerView';
import { JobDetailView } from './views/JobDetailView';
import { CompanyProfileView } from './views/CompanyProfileView';
import { SalaryCalculatorView } from './views/SalaryCalculatorView';
import { CandidatePortalView } from './views/CandidatePortalView';
import { RecruiterDashboardView } from './views/RecruiterDashboardView';
import { JobCreationWizardView } from './views/JobCreationWizardView';
import { RequestsManagerView } from './views/RequestsManagerView';
import { MessagesCenterView } from './views/MessagesCenterView';
import { AuthView } from './views/AuthView';

export default function App() {
  const [currentScreen, setCurrentScreen] = useState<AppScreen>('home');
  const [selectedJobId, setSelectedJobId] = useState<string>('stark-frontend-senior');
  const [selectedCompany, setSelectedCompany] = useState<string>('Stark Cloud Corp');
  const [selectedChatWorker, setSelectedChatWorker] = useState<string>('usr-trabajador-1');
  const [currentUser, setCurrentUser] = useState<User | null>({
    id: 'usr-cliente-1',
    name: 'Camila Ramos',
    email: 'camila.ramos@empresa.com',
    role: 'CLIENTE',
    status: 'ACTIVO',
    avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
    profession: 'Gerente de Contratación & Proyectos'
  });

  const [searchParams, setSearchParams] = useState({
    query: 'Senior Fullstack Developer',
    location: 'remoto-colombia',
    salary: 'cop-8m',
  });
  const [notificationsOpen, setNotificationsOpen] = useState(false);
  const [savedJobsCount, setSavedJobsCount] = useState(3);

  // Scroll to top whenever screen changes
  useEffect(() => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, [currentScreen]);

  const handleSelectJob = (jobId: string) => {
    setSelectedJobId(jobId);
    setCurrentScreen('job-detail');
  };

  const handleSelectCompany = (companyName: string) => {
    setSelectedCompany(companyName);
    setCurrentScreen('company-profile');
  };

  const handleSearchFromHome = (query: string, location: string, salary: string) => {
    setSearchParams({ query, location, salary });
    setCurrentScreen('explorer');
  };

  const handleExploreWithSalary = (salaryMin: number) => {
    setSearchParams(prev => ({
      ...prev,
      salary: salaryMin >= 10000000 ? 'cop-10m' : 'cop-8m'
    }));
    setCurrentScreen('explorer');
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#FBF9F5] text-[#2C2724] antialiased selection:bg-[#EAF4F4] selection:text-[#537A72] font-body">
      {/* Global Navigation Header */}
      <Navbar
        currentScreen={currentScreen}
        setScreen={setCurrentScreen}
        onOpenNotifications={() => setNotificationsOpen(true)}
        savedJobsCount={savedJobsCount}
      />

      {/* Main Content Area (padding-top accounts for fixed navbar) */}
      <main className="flex-grow pt-20 w-full">
        {currentScreen === 'home' && (
          <HomeView
            setScreen={setCurrentScreen}
            onSelectJob={handleSelectJob}
            onSearch={handleSearchFromHome}
          />
        )}

        {currentScreen === 'explorer' && (
          <JobsExplorerView
            setScreen={setCurrentScreen}
            onSelectJob={handleSelectJob}
            initialSearchQuery={searchParams.query}
            initialLocation={searchParams.location}
            initialSalary={searchParams.salary}
          />
        )}

        {currentScreen === 'requests' && (
          <RequestsManagerView
            currentUser={currentUser}
            setScreen={setCurrentScreen}
            onOpenChatWith={(wId) => {
              setSelectedChatWorker(wId);
              setCurrentScreen('messages');
            }}
          />
        )}

        {currentScreen === 'messages' && (
          <MessagesCenterView
            currentUser={currentUser}
            selectedWorkerId={selectedChatWorker}
          />
        )}

        {(currentScreen === 'login' || currentScreen === 'register' || currentScreen === 'recover-password') && (
          <AuthView
            initialMode={currentScreen === 'register' ? 'register' : (currentScreen === 'recover-password' ? 'recover' : 'login')}
            onLoginSuccess={(u) => {
              setCurrentUser(u);
              setCurrentScreen('requests');
            }}
            setScreen={setCurrentScreen}
          />
        )}

        {currentScreen === 'job-detail' && (
          <JobDetailView
            jobId={selectedJobId}
            setScreen={setCurrentScreen}
            onSelectCompany={handleSelectCompany}
            onApplySuccess={() => {
              setSavedJobsCount(prev => prev + 1);
            }}
          />
        )}

        {currentScreen === 'company-profile' && (
          <CompanyProfileView
            companyName={selectedCompany}
            setScreen={setCurrentScreen}
            onSelectJob={handleSelectJob}
          />
        )}

        {currentScreen === 'salary-calculator' && (
          <SalaryCalculatorView
            setScreen={setCurrentScreen}
            onExploreWithSalary={handleExploreWithSalary}
          />
        )}

        {currentScreen === 'candidate-portal' && (
          <CandidatePortalView
            setScreen={setCurrentScreen}
            onSelectJob={handleSelectJob}
          />
        )}

        {currentScreen === 'recruiter-dashboard' && (
          <RecruiterDashboardView
            setScreen={setCurrentScreen}
            onSelectJob={handleSelectJob}
          />
        )}

        {currentScreen === 'create-job' && (
          <JobCreationWizardView
            setScreen={setCurrentScreen}
          />
        )}
      </main>

      {/* Global Footer */}
      <Footer setScreen={setCurrentScreen} />

      {/* Notifications Drawer / Modal */}
      {notificationsOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/45 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#E8E2D8] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#F0EAE1] mb-4">
              <div className="flex items-center gap-2">
                <span className="material-symbols-outlined text-[#6B9080]">notifications</span>
                <h3 className="font-bold font-headline text-base text-[#2C2724]">
                  Centro de Notificaciones (Observer)
                </h3>
              </div>
              <button
                onClick={() => setNotificationsOpen(false)}
                className="w-8 h-8 rounded-full hover:bg-[#F6F3EC] flex items-center justify-center text-[#8C847C]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="space-y-3 font-body text-xs mb-6">
              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('requests');
                }}
                className="p-3.5 rounded-2xl bg-[#EAF4F4] border border-[#CCE3DE] cursor-pointer hover:border-[#6B9080] transition-colors"
              >
                <p className="font-bold text-[#537A72] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">event_available</span>
                  Solicitud Agendada con Éxito
                </p>
                <p className="text-[#56504A] mt-0.5">
                  "Módulo de Pagos Wompi" programado para el sábado 25 Octubre a las 9:30 AM.
                </p>
                <span className="text-[10px] text-[#8C847C] mt-1 block">Hace 15 min · State Pattern</span>
              </div>

              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('messages');
                }}
                className="p-3.5 rounded-2xl bg-[#F2F4FB] border border-[#DCD4F0] cursor-pointer hover:border-[#655B8B] transition-colors"
              >
                <p className="font-bold text-[#655B8B] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">chat</span>
                  Nuevo Mensaje de Valentina Gómez
                </p>
                <p className="text-[#56504A] mt-0.5">
                  "Te he compartido el enlace del prototipo en Figma con los comentarios resueltos."
                </p>
                <span className="text-[10px] text-[#8C847C] mt-1 block">Hace 1 hora</span>
              </div>

              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('requests');
                }}
                className="p-3.5 rounded-2xl bg-[#FEFAF0] border border-[#FDF1B8] cursor-pointer hover:border-[#8C7738] transition-colors"
              >
                <p className="font-bold text-[#8C7738] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">hotel_class</span>
                  Calificación 5 Estrellas Recibida ⭐
                </p>
                <p className="text-[#56504A] mt-0.5">
                  Se ha recalculado tu promedio general a 4.95 / 5.00 mediante el suscriptor de Reputación.
                </p>
                <span className="text-[10px] text-[#8C847C] mt-1 block">Ayer</span>
              </div>
            </div>

            <button
              onClick={() => setNotificationsOpen(false)}
              className="w-full py-2.5 rounded-xl bg-[#F6F3EC] hover:bg-[#EAE4D9] text-xs font-bold text-[#2C2724] transition-colors cursor-pointer"
            >
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
