<?php
/**
 * STRATEGY PATTERN - Échele Camello
 * Conforme a 05_PATRONES_DISENO_ECHELE_CAMELLO.md (Sección 7)
 * Permite seleccionar la estrategia de ordenamiento de resultados de búsqueda
 * de forma desacoplada y determinista (Cercanía Haversine, Calificación, Salario, Reciente).
 */

namespace EcheleCamello\Patterns\Strategy;

interface OrdenamientoBusquedaStrategy {
    public function getNombre(): string;
    /**
     * @param array $resultados Lista de elementos a ordenar
     * @param array $contexto Parámetros adicionales (ej. lat/lng del usuario origen)
     * @return array Lista ordenada
     */
    public function ordenar(array $resultados, array $contexto = []): array;
}

/**
 * Estrategia 1: Orden por Cercanía Geográfica usando Fórmula Haversine
 */
class OrdenPorCercaniaStrategy implements OrdenamientoBusquedaStrategy {
    public function getNombre(): string { return 'cercania'; }

    public function ordenar(array $resultados, array $contexto = []): array {
        $origenLat = (float)($contexto['user_lat'] ?? 4.7110); // Bogotá por defecto
        $origenLng = (float)($contexto['user_lng'] ?? -74.0721);

        // Precalcular distancias
        foreach ($resultados as &$item) {
            $destLat = (float)($item['lat'] ?? 4.7110);
            $destLng = (float)($item['lng'] ?? -74.0721);
            $item['_distancia_km'] = self::calcularHaversine($origenLat, $origenLng, $destLat, $destLng);
        }
        unset($item);

        // Ordenamiento determinista con desempate por ID
        usort($resultados, function($a, $b) {
            if ($a['_distancia_km'] == $b['_distancia_km']) {
                return strcmp($a['id'] ?? '', $b['id'] ?? '');
            }
            return ($a['_distancia_km'] < $b['_distancia_km']) ? -1 : 1;
        });

        return $resultados;
    }

    public static function calcularHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float {
        $radioTierraKm = 6371.0;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($radioTierraKm * $c, 2);
    }
}

/**
 * Estrategia 2: Orden por Calificación (Reputación 1-5 estrellas)
 */
class OrdenPorCalificacionStrategy implements OrdenamientoBusquedaStrategy {
    public function getNombre(): string { return 'calificacion'; }

    public function ordenar(array $resultados, array $contexto = []): array {
        usort($resultados, function($a, $b) {
            $rA = (float)($a['rating_avg'] ?? 5.0);
            $rB = (float)($b['rating_avg'] ?? 5.0);
            if ($rA == $rB) {
                return strcmp($a['id'] ?? '', $b['id'] ?? '');
            }
            return ($rA > $rB) ? -1 : 1; // Mayor calificación primero
        });
        return $resultados;
    }
}

/**
 * Estrategia 3: Orden por Salario Máximo
 */
class OrdenPorSalarioStrategy implements OrdenamientoBusquedaStrategy {
    public function getNombre(): string { return 'salario'; }

    public function ordenar(array $resultados, array $contexto = []): array {
        usort($resultados, function($a, $b) {
            $sA = (float)($a['salary_max'] ?? $a['salary_min'] ?? 0);
            $sB = (float)($b['salary_max'] ?? $b['salary_min'] ?? 0);
            if ($sA == $sB) {
                return strcmp($a['id'] ?? '', $b['id'] ?? '');
            }
            return ($sA > $sB) ? -1 : 1; // Mayor salario primero
        });
        return $resultados;
    }
}

/**
 * Estrategia 4: Orden por Más Reciente
 */
class OrdenPorRecienteStrategy implements OrdenamientoBusquedaStrategy {
    public function getNombre(): string { return 'reciente'; }

    public function ordenar(array $resultados, array $contexto = []): array {
        usort($resultados, function($a, $b) {
            $tA = strtotime($a['created_at'] ?? '2026-01-01');
            $tB = strtotime($b['created_at'] ?? '2026-01-01');
            if ($tA == $tB) {
                return strcmp($a['id'] ?? '', $b['id'] ?? '');
            }
            return ($tA > $tB) ? -1 : 1;
        });
        return $resultados;
    }
}

/**
 * Contexto del Buscador que utiliza la Estrategia Inyectada
 */
class BusquedaContext {
    private OrdenamientoBusquedaStrategy $estrategia;

    public function __construct(?OrdenamientoBusquedaStrategy $estrategiaInicial = null) {
        $this->estrategia = $estrategiaInicial ?: new OrdenPorRecienteStrategy();
    }

    public function setEstrategia(OrdenamientoBusquedaStrategy $estrategia): void {
        $this->estrategia = $estrategia;
    }

    public static function resolverEstrategia(string $criterio): OrdenamientoBusquedaStrategy {
        switch (strtolower(trim($criterio))) {
            case 'cercania':
                return new OrdenPorCercaniaStrategy();
            case 'calificacion':
                return new OrdenPorCalificacionStrategy();
            case 'salario':
                return new OrdenPorSalarioStrategy();
            case 'reciente':
            default:
                return new OrdenPorRecienteStrategy();
        }
    }

    public function ejecutar(array $items, array $contexto = []): array {
        return $this->estrategia->ordenar($items, $contexto);
    }
}
