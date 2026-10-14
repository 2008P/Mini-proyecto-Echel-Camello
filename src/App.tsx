/**
 * @license
 * SPDX-License-Identifier: Apache-2.0
 */

import React, { useState, useEffect } from 'react';
import { AppScreen } from './types';
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

export default function App() {
  const [currentScreen, setCurrentScreen] = useState<AppScreen>('home');
  const [selectedJobId, setSelectedJobId] = useState<string>('stark-frontend-senior');
  const [selectedCompany, setSelectedCompany] = useState<string>('Stark Cloud Corp');
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
    <div className="min-h-screen flex flex-col bg-[#fff8f5] text-[#1f1b18] antialiased selection:bg-[#ffdbd0] selection:text-[#a83301]">
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
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fade-in">
          <div className="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-[#e5e5e5] relative animate-scale-up">
            <div className="flex items-center justify-between pb-3 border-b border-[#f0f0f0] mb-4">
              <div className="flex items-center gap-2">
                <span className="material-symbols-outlined text-[#a83301]">notifications</span>
                <h3 className="font-bold font-headline text-base text-[#1f1b16]">
                  Notificaciones de Camello
                </h3>
              </div>
              <button
                onClick={() => setNotificationsOpen(false)}
                className="w-8 h-8 rounded-full hover:bg-neutral-100 flex items-center justify-center text-[#737373]"
              >
                <span className="material-symbols-outlined text-lg">close</span>
              </button>
            </div>

            <div className="space-y-3 font-body text-xs mb-6">
              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('candidate-portal');
                }}
                className="p-3 rounded-xl bg-[#fff4f0] border border-[#ffdbd0] cursor-pointer hover:border-[#a83301] transition-colors"
              >
                <p className="font-bold text-[#a83301] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm">workspace_premium</span>
                  ¡Oferta formal de Stark Cloud Corp!
                </p>
                <p className="text-[#525252] mt-0.5">
                  USD $4,200 / mes lista para tu revisión y firma digital antes del viernes.
                </p>
                <span className="text-[10px] text-[#737373] mt-1 block">Hace 2 horas</span>
              </div>

              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('candidate-portal');
                }}
                className="p-3 rounded-xl bg-neutral-50 border border-neutral-200 cursor-pointer hover:border-[#0284c7] transition-colors"
              >
                <p className="font-bold text-[#1f1b16] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm text-[#0284c7]">video_call</span>
                  Entrevista agendada con Nubank
                </p>
                <p className="text-[#525252] mt-0.5">
                  Jueves 24 Oct, 3:00 PM (45 min) con Tomás Restrepo (VP Engineering).
                </p>
                <span className="text-[10px] text-[#737373] mt-1 block">Ayer</span>
              </div>

              <div
                onClick={() => {
                  setNotificationsOpen(false);
                  setCurrentScreen('explorer');
                }}
                className="p-3 rounded-xl bg-neutral-50 border border-neutral-200 cursor-pointer hover:border-[#16a34a] transition-colors"
              >
                <p className="font-bold text-[#1f1b16] flex items-center gap-1">
                  <span className="material-symbols-outlined text-sm text-[#16a34a]">campaign</span>
                  8 nuevas vacantes en Tecnología
                </p>
                <p className="text-[#525252] mt-0.5">
                  Empresas verificadas han publicado vacantes 100% remotas acordes a tu perfil.
                </p>
                <span className="text-[10px] text-[#737373] mt-1 block">Hace 2 días</span>
              </div>
            </div>

            <button
              onClick={() => setNotificationsOpen(false)}
              className="w-full py-2.5 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-xs font-bold text-[#1f1b16] transition-colors"
            >
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
