import { Component, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { CommonModule } from '@angular/common';
import {
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonButton,
} from '@ionic/angular/standalone';
import { EmployeeService } from 'src/app/services/employee.service';
import { Router } from '@angular/router';
import { RoleService } from 'src/app/services/role.service';
import { Employee } from 'src/app/models/Employee';
import { EmployeeRol } from 'src/app/models/Employee-rol';

@Component({
  selector: 'app-enter-pin-modal',
  templateUrl: './enter-pin-modal.component.html',
  styleUrls: ['./enter-pin-modal.component.scss'],
  standalone: true,
  imports: [
    CommonModule,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonContent,
    IonGrid,
    IonRow,
    IonCol,
    IonButton,
  ],
})
export class EnterPinModalComponent {
  pin: string = '';
  pinLength: string = '';
  error = false;
  employeeRol!: EmployeeRol;
  @Input() idEmployee: number = 0;

  constructor(
    private modalController: ModalController,
    private es: EmployeeService,
    private rs: RoleService,
    private router: Router
  ) {}

  appendDigit(digit: string) {
    if (this.pin.length < 4) {
      this.pin += digit;
      this.pinLength += '*';
      this.error = false;
    }
  }

  deleteDigit() {
    this.pin = this.pin.slice(0, -1);
    this.pinLength = this.pinLength.slice(0, -1);
  }

  submitPin() {
    if (this.pin.length === 4) {
      this.es.postLoginEmployee(this.idEmployee, Number(this.pin)).subscribe({
        next: (data) => {
          if (data.id) {
            localStorage.setItem('idEmployee', data.id.toString());
            this.getRole(data);
          }
        },
        error: (err) => {
          this.pin = '';
          this.pinLength = '';
          this.error = true;
        },
      });
    }
  }

  getRole(employee: Employee) {
    this.rs.getRole(employee.idRol!).subscribe({
      next: (data) => {
        this.employeeRol = new EmployeeRol({
          idEmpleado: employee.id,
          nombre: employee.nombre,
          id: data.id,
          rol: data.rol,
          productos: data.productos,
          categorias: data.categorias,
          tpv: data.tpv,
          usuarios: data.usuarios,
          mesas: data.mesas,
          restaurante: data.restaurante,
          clientes: data.clientes,
          empresa: data.empresa,
          pago: data.pago,
          idEmpresa: employee.idEmpresa,
        });
        localStorage.setItem("employeeRol", JSON.stringify(this.employeeRol));
        this.modalController.dismiss(null);
        this.router.navigate(['/employees/panel']);
      },
      error: (err) => {
        // Maneja el error según sea necesario, si deseas manejarlo de alguna otra manera
      },
    });
  }

  cancel() {
    this.modalController.dismiss(null);
  }
}
