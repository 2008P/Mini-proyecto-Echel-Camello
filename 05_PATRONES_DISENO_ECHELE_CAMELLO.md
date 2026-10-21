# Échele Camello: patrones de diseño por funcionalidad

**Documento:** guía de implementación para Antigravity IDE  
**Autor del proyecto:** Luis Yamit Tombe Cabrera — ADSO, ficha 3413988  
**Fecha:** 14 de octubre de 2026  

## 1. Patrones de Diseño Implementados
- **Repository + DTO**: En persistencia/API para Usuarios, Solicitudes, Conversaciones, Mensajes, Notificaciones, Calificaciones y Reportes.
- **State Pattern**: En el ciclo de vida de Solicitudes (`PENDIENTE` -> `ACEPTADA`/`RECHAZADA` -> `PROGRAMADA` -> `EN_PROCESO` -> `FINALIZADA` / `CANCELADA`) y Reportes de Moderación.
- **Strategy Pattern**: En Búsqueda y Ordenamiento dinámico (`OrdenPorCercaniaStrategy` con Haversine, `OrdenPorCalificacionStrategy`, `OrdenPorSalarioStrategy`, `OrdenPorRecienteStrategy`).
- **Observer / PubSub Pattern**: Desacoplamiento de eventos de dominio (`SolicitudCreadaEvent`, `SolicitudAceptadaEvent`, `SolicitudProgramadaEvent`, `SolicitudFinalizadaEvent`, `CalificacionRegistradaEvent`) hacia el módulo de notificaciones y recálculo de promedios.
- **Adapter Pattern**: `CorreoPort` (`SmtpCorreoAdapter`, `SimuladoCorreoAdapter`) y `AlmacenamientoArchivosPort` (`AlmacenamientoLocalAdapter`) para aislar dependencias de infraestructura.
- **Inyección de Dependencias & Capas**: Composición limpia de servicios sin Singleton manual (`getInstance`).
