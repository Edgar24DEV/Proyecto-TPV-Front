import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule } from '@ionic/angular';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RoleService } from 'src/app/services/role.service';
import { Role } from 'src/app/models/Role';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-role-admin',
  templateUrl: './role-admin.component.html',
  styleUrls: ['./role-admin.component.scss'],
  imports: [ IonicModule, CommonModule, ReactiveFormsModule],
})
export class RoleAdminComponent implements OnInit {
  roleForm!: FormGroup;
  idCompany!: number;
  idRestaurant!: number;
  listRoles: Role[] = [];
  role!: Role;
  isDisabled: boolean = false;
  showRoleModal: boolean = false;
  createRole: boolean = false;
  results: Role[] = [];
  formSubmitted: boolean = false;
  errorMessage: string = '';
  error:boolean=false;

  constructor(
    private fb: FormBuilder,
    private rS: RoleService,
    private router: Router,
    private alertService: AlertService
  ) {}

  ngOnInit() {
    const storedCompany = localStorage.getItem('idCompany');

    if (storedCompany) {
      this.idCompany = Number(storedCompany);
    }
    
    if (!storedCompany) {
      this.router.navigate(['']);
    }
   
    this.listOfRoles();
    this.roleForm = this.fb.group({
      rol: ['', [Validators.required, Validators.maxLength(255)]],
      productos: false,
      categorias: false,
      tpv: false,
      usuarios: false,
      mesas: false,
      restaurante: false,
      clientes: false,
      empresa: false,
      pago: false,
    });
  }

  listOfRoles() {
    this.rS.getRolesCompany(this.idCompany).subscribe({
      next: (data) => {
        this.listRoles = data;
        this.results = data;
      },
      error: (e) => {
        console.error('Error al cargar los roles:', e);
      },
    });
  }
  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    this.results = this.listRoles.filter((d) =>
      d.rol!.toLowerCase().includes(query)
    );
  }
  showCreate() {
    this.showRoleModal = true;
    this.createRole = true;
    this.isDisabled = false;
    this.enableCheckboxes();
    this.roleForm.patchValue({
      rol: [''],
      productos: false,
      categorias: false,
      tpv: false,
      usuarios: false,
      mesas: false,
      restaurante: false,
      clientes: false,
      empresa: false,
      pago: false,
    });
  }

  showEdit(id: number) {
    this.showRoleModal = true;
    this.getRole(id);
    this.createRole = false;
    this.isDisabled = false;
    this.enableCheckboxes();
    this.errorMessage='';
    this.error=false;
  }

  showInfo(id: number) {
    this.showRoleModal = true;
    this.getRole(id);
    this.createRole = false;
    this.isDisabled = true;
    this.disableCheckboxes();
    this.errorMessage='';
    this.error=false;
  }
  cancel() {
    this.showRoleModal = false;
    this.errorMessage='';
    this.error=false;
  }

  getRole(id: number) {
    this.rS.getRole(id).subscribe({
      next: (data) => {
        this.role = data;
        this.roleForm.patchValue({
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
        });
      },
      error(err) {
        console.error('Error al cargar el cliente ', err);
      },
    });
  }
  saveRole() {
    this.formSubmitted = true;
    if (this.roleForm.invalid) {
      this.roleForm.markAllAsTouched();
      return;
    }

    let newRole = new Role({
      rol: this.roleForm.value.rol,
      productos: this.roleForm.value.productos,
      categorias: this.roleForm.value.categorias,
      tpv: this.roleForm.value.tpv,
      usuarios: this.roleForm.value.usuarios,
      mesas: this.roleForm.value.mesas,
      restaurante: this.roleForm.value.restaurante,
      clientes: this.roleForm.value.clientes,
      empresa: this.roleForm.value.empresa,
      pago: this.roleForm.value.pago,
    });

    if (this.createRole) {
      this.rS.addRole(newRole, this.idCompany!).subscribe({
        next: (data) => {
          this.showRoleModal = false;
          this.listOfRoles();
        },
        error: (err) => {
          this.alertService.show('Error', 'Error al crear el rol.', 'error')
          this.errorMessage = err.error?.message || 'Error desconocido';
          this.error = true;
        }
      });
    }
    if (!this.createRole) {
      newRole.id = this.role.id;
      this.rS.updateRole(newRole, this.idCompany!).subscribe({
        next: (data) => {
          this.showRoleModal = false;
          this.listOfRoles();
        },
        error:(err) => {
          this.alertService.show('Error', 'Error al actualizar el rol.', 'error')
          this.errorMessage = err.error?.message || 'Error desconocido';
          this.error = true;
        },
      });
    }
  }

  delete() {
    let idRole = this.role.id;

    this.rS.deleteRole(idRole!).subscribe({
      next: () => {
        this.listOfRoles();
        this.showRoleModal = false;
      },
      error: (err) => {
        this.alertService.show('Error', 'Error al eliminar el rol.', 'error')
      },
    });
  }

  isInvalid(controlName: string): boolean {
    const control = this.roleForm.get(controlName);
    return !!(
      control &&
      control.invalid &&
      (control.touched || this.formSubmitted)
    );
  }
  disableCheckboxes() {
    Object.keys(this.roleForm.controls).forEach((controlName) => {
      if (controlName !== 'rol') {
        this.roleForm.get(controlName)?.disable();
      }
    });
  }

  enableCheckboxes() {
    Object.keys(this.roleForm.controls).forEach((controlName) => {
      if (controlName !== 'rol') {
        this.roleForm.get(controlName)?.enable();
      }
    });
  }
}
