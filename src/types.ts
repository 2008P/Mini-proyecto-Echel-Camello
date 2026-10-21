export type AppScreen = 
  | 'home' 
  | 'explorer' 
  | 'job-detail' 
  | 'company-profile' 
  | 'salary-calculator' 
  | 'candidate-portal' 
  | 'recruiter-dashboard' 
  | 'create-job'
  | 'requests'
  | 'messages'
  | 'notifications'
  | 'login'
  | 'register'
  | 'recover-password';

export type UserRole = 'CLIENTE' | 'TRABAJADOR' | 'ADMINISTRADOR';

export interface User {
  id: string;
  name: string;
  email: string;
  role: UserRole;
  status: 'PENDIENTE' | 'ACTIVO' | 'SUSPENDIDO';
  avatar?: string;
  phone?: string;
  location?: string;
  profession?: string;
  ratingAvg?: number;
  ratingCount?: number;
}

export type EstadoSolicitudEnum = 
  | 'PENDIENTE' 
  | 'ACEPTADA' 
  | 'RECHAZADA' 
  | 'PROGRAMADA' 
  | 'EN_PROCESO' 
  | 'FINALIZADA' 
  | 'CANCELADA';

export interface Solicitud {
  id: string;
  clienteId: string;
  clienteNombre: string;
  clienteAvatar?: string;
  trabajadorId: string;
  trabajadorNombre: string;
  trabajadorAvatar?: string;
  trabajadorProfesion?: string;
  servicioId?: string;
  titulo: string;
  descripcion: string;
  estado: EstadoSolicitudEnum;
  fechaProgramada?: string;
  direccion?: string;
  valorPropuesto: number;
  valorAcordado?: number;
  motivoCancelacion?: string;
  createdAt: string;
}

export interface Conversacion {
  id: string;
  clienteId: string;
  clienteNombre: string;
  clienteAvatar?: string;
  trabajadorId: string;
  trabajadorNombre: string;
  trabajadorAvatar?: string;
  solicitudId?: string;
  ultimoMensaje?: string;
  ultimoMensajeAt: string;
  noLeidos?: number;
}

export interface Mensaje {
  id: string;
  conversacionId: string;
  remitenteId: string;
  contenido: string;
  adjuntoUrl?: string;
  adjuntoTipo?: 'image' | 'file';
  leido: boolean;
  createdAt: string;
}

export interface Notificacion {
  id: string;
  usuarioId: string;
  titulo: string;
  mensaje: string;
  tipo: 'solicitud' | 'mensaje' | 'calificacion' | 'sistema';
  enlace?: string;
  leida: boolean;
  createdAt: string;
}

export interface Job {
  id: string;
  title: string;
  company: string;
  companyLogo?: string;
  location: string;
  modality: '100% Remoto' | 'Híbrido' | 'Presencial';
  salaryCOP?: string;
  salaryUSD?: string;
  salaryMin?: number;
  salaryMax?: number;
  currency: 'COP' | 'USD';
  salaryPeriod: string;
  contractType: string;
  experienceLevel: 'Junior' | 'Semi-Senior' | 'Senior' | 'Lead & Staff';
  category: string;
  tags: string[];
  perks: string[];
  postedTime: string;
  urgency?: 'Urgente' | 'Destacada' | '1 Clic' | 'Rápido' | 'Verificado' | 'Activa';
  verifiedCompany?: boolean;
  responseTime?: string;
  activeCandidates?: number;
  description: string;
  responsibilities?: string[];
  requirements?: string[];
  hiringManager?: {
    name: string;
    role: string;
    avatar: string;
  };
}

export interface Candidate {
  id: string;
  name: string;
  role: string;
  experience: string;
  location: string;
  avatar: string;
  matchScore: number;
  vacancyTarget: string;
  salaryExpectation: string;
  skills: string[];
  englishLevel?: string;
  techTestScore?: string;
}

export interface UserApplication {
  id: string;
  jobId: string;
  title: string;
  company: string;
  companyLogo: string;
  location: string;
  status: 'offer' | 'interview' | 'tech_test' | 'review' | 'archived';
  statusLabel: string;
  statusColor: string;
  salaryDisplay: string;
  contractType: string;
  appliedDaysAgo: number;
  stageProgress: string;
  progressPercent: number;
  tags: string[];
  actionRequired?: {
    title: string;
    description: string;
    deadline?: string;
    primaryBtnText: string;
    type: 'review_offer' | 'join_interview' | 'submit_test' | 'view_application';
  };
  interviewDetails?: {
    topic: string;
    datetime: string;
    interviewer: string;
    platform: string;
  };
  testDetails?: {
    topic: string;
    timeRemaining: string;
    progressPercent: number;
    lastSaved: string;
  };
}

export interface Testimonial {
  id: string;
  quote: string;
  author: string;
  role: string;
  avatar: string;
  hiredIn: string;
}

export interface FilterState {
  searchQuery: string;
  location: string;
  salaryRange: string;
  modalities: string[];
  salaryRanges: string[];
  contractTypes: string[];
  seniorities: string[];
  perks: string[];
  recency: string;
  fastApplyOnly: boolean;
  sortBy: string;
}
