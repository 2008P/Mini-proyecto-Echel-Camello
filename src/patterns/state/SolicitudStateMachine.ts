/**
 * PATRÓN STATE EN TYPESCRIPT - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 8)
 * Encapsula transiciones y comportamiento según el estado actual de la solicitud.
 */

import { EstadoSolicitudEnum, Solicitud } from '../../types';

export interface SolicitudState {
  readonly name: EstadoSolicitudEnum;
  aceptar(context: SolicitudStateMachine, actorId: string): void;
  rechazar(context: SolicitudStateMachine, actorId: string, motivo: string): void;
  programar(context: SolicitudStateMachine, actorId: string, fechaHora: string, valor: number): void;
  iniciar(context: SolicitudStateMachine, actorId: string): void;
  finalizar(context: SolicitudStateMachine, actorId: string): void;
  reprogramar(context: SolicitudStateMachine, actorId: string, fechaHora: string): void;
  cancelar(context: SolicitudStateMachine, actorId: string, motivo: string): void;
}

abstract class BaseSolicitudState implements SolicitudState {
  abstract readonly name: EstadoSolicitudEnum;

  protected invalid(action: string): never {
    throw new Error(`Acción '${action}' no permitida en estado ${this.name}.`);
  }

  aceptar(): void { this.invalid('aceptar'); }
  rechazar(): void { this.invalid('rechazar'); }
  programar(): void { this.invalid('programar'); }
  iniciar(): void { this.invalid('iniciar'); }
  finalizar(): void { this.invalid('finalizar'); }
  reprogramar(): void { this.invalid('reprogramar'); }
  cancelar(): void { this.invalid('cancelar'); }
}

export class SolicitudPendienteState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'PENDIENTE';

  aceptar(context: SolicitudStateMachine, actorId: string): void {
    if (context.solicitud.trabajadorId !== actorId) {
      throw new Error('Solo el trabajador puede aceptar esta solicitud.');
    }
    context.transitionTo(new SolicitudAceptadaState());
  }

  rechazar(context: SolicitudStateMachine, actorId: string, motivo: string): void {
    if (context.solicitud.trabajadorId !== actorId) {
      throw new Error('Solo el trabajador puede rechazar esta solicitud.');
    }
    context.solicitud.motivoCancelacion = motivo || 'Rechazada por el trabajador.';
    context.transitionTo(new SolicitudRechazadaState());
  }

  cancelar(context: SolicitudStateMachine, actorId: string, motivo: string): void {
    context.solicitud.motivoCancelacion = motivo || 'Cancelada por el usuario.';
    context.transitionTo(new SolicitudCanceladaState());
  }
}

export class SolicitudAceptadaState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'ACEPTADA';

  programar(context: SolicitudStateMachine, _actorId: string, fechaHora: string, valor: number): void {
    if (!fechaHora) throw new Error('Debe especificar fecha y hora.');
    context.solicitud.fechaProgramada = fechaHora;
    if (valor > 0) context.solicitud.valorAcordado = valor;
    context.transitionTo(new SolicitudProgramadaState());
  }

  cancelar(context: SolicitudStateMachine, _actorId: string, motivo: string): void {
    context.solicitud.motivoCancelacion = motivo;
    context.transitionTo(new SolicitudCanceladaState());
  }
}

export class SolicitudProgramadaState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'PROGRAMADA';

  iniciar(context: SolicitudStateMachine, actorId: string): void {
    if (context.solicitud.trabajadorId !== actorId) {
      throw new Error('Solo el trabajador puede iniciar la ejecución del camello.');
    }
    context.transitionTo(new SolicitudEnProcesoState());
  }

  reprogramar(context: SolicitudStateMachine, _actorId: string, fechaHora: string): void {
    context.solicitud.fechaProgramada = fechaHora;
  }

  cancelar(context: SolicitudStateMachine, _actorId: string, motivo: string): void {
    context.solicitud.motivoCancelacion = motivo;
    context.transitionTo(new SolicitudCanceladaState());
  }
}

export class SolicitudEnProcesoState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'EN_PROCESO';

  finalizar(context: SolicitudStateMachine): void {
    context.transitionTo(new SolicitudFinalizadaState());
  }

  cancelar(context: SolicitudStateMachine, _actorId: string, motivo: string): void {
    context.solicitud.motivoCancelacion = `Cancelado durante ejecución: ${motivo}`;
    context.transitionTo(new SolicitudCanceladaState());
  }
}

export class SolicitudFinalizadaState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'FINALIZADA';
}

export class SolicitudRechazadaState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'RECHAZADA';
}

export class SolicitudCanceladaState extends BaseSolicitudState {
  readonly name: EstadoSolicitudEnum = 'CANCELADA';
}

/**
 * CONTEXTO STATE MACHINE
 */
export class SolicitudStateMachine {
  public solicitud: Solicitud;
  private state: SolicitudState;
  private onStateChange?: (solicitud: Solicitud) => void;

  constructor(solicitud: Solicitud, onStateChange?: (solicitud: Solicitud) => void) {
    this.solicitud = { ...solicitud };
    this.onStateChange = onStateChange;
    this.state = this.resolveState(solicitud.estado);
  }

  private resolveState(estado: EstadoSolicitudEnum): SolicitudState {
    switch (estado) {
      case 'PENDIENTE': return new SolicitudPendienteState();
      case 'ACEPTADA': return new SolicitudAceptadaState();
      case 'RECHAZADA': return new SolicitudRechazadaState();
      case 'PROGRAMADA': return new SolicitudProgramadaState();
      case 'EN_PROCESO': return new SolicitudEnProcesoState();
      case 'FINALIZADA': return new SolicitudFinalizadaState();
      case 'CANCELADA': return new SolicitudCanceladaState();
      default: return new SolicitudPendienteState();
    }
  }

  public transitionTo(newState: SolicitudState): void {
    this.state = newState;
    this.solicitud.estado = newState.name;
    if (this.onStateChange) {
      this.onStateChange(this.solicitud);
    }
  }

  public aceptar(actorId: string): void { this.state.aceptar(this, actorId); }
  public rechazar(actorId: string, motivo: string): void { this.state.rechazar(this, actorId, motivo); }
  public programar(actorId: string, fechaHora: string, valor: number): void { this.state.programar(this, actorId, fechaHora, valor); }
  public iniciar(actorId: string): void { this.state.iniciar(this, actorId); }
  public finalizar(actorId: string): void { this.state.finalizar(this, actorId); }
  public reprogramar(actorId: string, fechaHora: string): void { this.state.reprogramar(this, actorId, fechaHora); }
  public cancelar(actorId: string, motivo: string): void { this.state.cancelar(this, actorId, motivo); }

  public getStateName(): EstadoSolicitudEnum {
    return this.state.name;
  }
}
