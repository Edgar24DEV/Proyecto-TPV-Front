# TPV Restaurante 🍽️

Sistema de Terminal Punto de Venta (TPV) para restaurantes, desarrollado con **Angular + Ionic** para el frontend y **Laravel** con arquitectura **DDD + Hexagonal** en el backend.

---

## 📦 Tecnologías

### Frontend
- [Angular](https://angular.io/) (Standalone Components)
- [Ionic](https://ionicframework.com/)
- RxJS (`firstValueFrom`, Observables)
- Componentes personalizados: teclado numérico, modales, etc.

### Backend
- [Laravel](https://laravel.com/)
- Arquitectura DDD (Domain-Driven Design)
- Patrón Hexagonal (Ports & Adapters)
- Repositorios desacoplados (sin Eloquent)

---

## 🎯 Funcionalidades Principales

- Autenticación de empleados por PIN
- Gestión de pedidos:
  - Añadir/editar/eliminar líneas
  - Teclado personalizado para totales y cantidades
- Impresión de tickets en PDF (con desglose de IVA)
- Gestión visual de mesas (drag & drop + persistencia en DB)
- Modal interactivo para número de comensales
- Modal de búsqueda y alta de clientes (por CIF)

---

## 🧱 Arquitectura

### Frontend
- Componentes desacoplados y reutilizables
- Comunicación entre componentes padre e hijo
- Uso intensivo de modales personalizados y control total del flujo

### Backend
- Casos de uso invocables desde controladores
- Entidades y servicios de dominio puros
- Interfaces de repositorio y adaptadores concretos
- Separación estricta entre capas de aplicación y dominio

---

## 🚀 Instalación

### Frontend

```bash
npm install
ionic serve
