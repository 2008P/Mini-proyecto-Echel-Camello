/**
 * STRATEGY PATTERN EN TYPESCRIPT - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 7)
 * Permite seleccionar el algoritmo de ordenamiento de resultados de búsqueda.
 */

import { Job } from '../../types';

export interface SortStrategy {
  readonly id: string;
  readonly label: string;
  sort(items: Job[], context?: { userLat?: number; userLng?: number }): Job[];
}

export class NearnessStrategy implements SortStrategy {
  readonly id = 'cercania';
  readonly label = 'Cercanía Haversine (Strategy)';

  sort(items: Job[], context?: { userLat?: number; userLng?: number }): Job[] {
    const lat1 = context?.userLat ?? 4.7110;
    const lon1 = context?.userLng ?? -74.0721;

    return [...items].sort((a, b) => {
      // Cálculo Haversine simulado con distancia consistente
      const distA = Math.abs(a.location.includes('Remoto') ? 5 : (a.location.includes('Bogotá') ? 2 : 250));
      const distB = Math.abs(b.location.includes('Remoto') ? 5 : (b.location.includes('Bogotá') ? 2 : 250));
      if (distA === distB) return a.id.localeCompare(b.id);
      return distA - distB;
    });
  }
}

export class RatingStrategy implements SortStrategy {
  readonly id = 'calificacion';
  readonly label = 'Mejor Calificación (Strategy)';

  sort(items: Job[]): Job[] {
    return [...items].sort((a, b) => {
      const rA = a.verifiedCompany ? 5 : 4.5;
      const rB = b.verifiedCompany ? 5 : 4.5;
      if (rA === rB) return a.id.localeCompare(b.id);
      return rB - rA;
    });
  }
}

export class SalaryStrategy implements SortStrategy {
  readonly id = 'salary';
  readonly label = 'Mayor Salario (Strategy)';

  sort(items: Job[]): Job[] {
    return [...items].sort((a, b) => {
      const sA = a.salaryMax ?? a.salaryMin ?? 0;
      const sB = b.salaryMax ?? b.salaryMin ?? 0;
      if (sA === sB) return a.id.localeCompare(b.id);
      return sB - sA;
    });
  }
}

export class RecentStrategy implements SortStrategy {
  readonly id = 'recent';
  readonly label = 'Más Recientes (Strategy)';

  sort(items: Job[]): Job[] {
    return [...items].sort((a, b) => a.id.localeCompare(b.id));
  }
}

export class SearchSortContext {
  private strategy: SortStrategy;

  constructor(strategy?: SortStrategy) {
    this.strategy = strategy ?? new RecentStrategy();
  }

  public setStrategy(strategy: SortStrategy): void {
    this.strategy = strategy;
  }

  public static resolve(criterion: string): SortStrategy {
    switch (criterion) {
      case 'cercania': return new NearnessStrategy();
      case 'calificacion': return new RatingStrategy();
      case 'salary': return new SalaryStrategy();
      case 'recent':
      default: return new RecentStrategy();
    }
  }

  public execute(items: Job[], context?: { userLat?: number; userLng?: number }): Job[] {
    return this.strategy.sort(items, context);
  }
}
