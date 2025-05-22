import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule, AlertController } from '@ionic/angular';
import {
  IonContent,
  IonGrid,
  IonRow,
  IonCol,
  IonLabel,
} from '@ionic/angular/standalone';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { EmployeeService } from 'src/app/services/employee.service';
import { Employee } from 'src/app/models/Employee';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { RoleService } from 'src/app/services/role.service';
import { Role } from 'src/app/models/Role';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { Restaurant } from 'src/app/models/Restaurant';
import { Route, Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';
import { AlertComponent } from "../alert/alert.component";

@Component({
  selector: 'app-users-admin',
  templateUrl: './users-admin.component.html',
  styleUrls: ['./users-admin.component.scss'],
  imports: [IonicModule, CommonModule, ReactiveFormsModule],
})
export class UsersAdminComponent implements OnInit {
  loadEmployees(loadEmployees: any) {
    throw new Error('Method not implemented.');
  }
  employeeForm!: FormGroup;
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  listEmployees: Employee[] = [];
  listRols: Role[] = [];
  listRestaurants: Restaurant[] = [];
  employee!: Employee;
  isDisabled: boolean = false;
  showEmployeeModal: boolean = false;
  createEmployee: boolean = false;
  companyName!: string;
  listEmployeeRestaurant: Restaurant[] = [];
  showRestaurantModal: boolean = false;
  restaurantForm!: FormGroup;
  listAvaibleRestaurants: Restaurant[] = [];

  constructor(
    private userService: EmployeeService,
    private roleService: RoleService,
    private restaurantService: RestaurantService,
    private fb: FormBuilder,
    private alertController: AlertController,
    private router: Router,
    private alertService: AlertService,
  ) {}

  ngOnInit() {
    this.initializeData();

    this.employeeForm = this.fb.group({
      nombre: ['', Validators.required],
      rol: ['', [Validators.required]],
      restaurant: '',
      pin: ['', [Validators.required]],
    });
    this.restaurantForm = this.fb.group({
      restaurant: ['', Validators.required]
    });
  }

  isInvalid(controlName: string): boolean {
    const control = this.employeeForm.get(controlName);
    return !!(control && control.invalid && (control.touched || this.employeeForm));
  }

  ionViewWillEnter() {
    this.initializeData();
  }
  private initializeData() {
    const storedCompany = localStorage.getItem('idCompany');
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
      this.companyName = localStorage.getItem('company')!;
      this.listOfRoles();
      this.listOfRestaurants();
    }

    const storedRestaurant = localStorage.getItem('idRestaurant');
    if (storedRestaurant && !storedCompany) {
      this.idRestaurant = Number(storedRestaurant);
    }

    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
      this.listOfRoles();
    }

    if(!this.idCompany || this.idRestaurant){
      if(!this.employeeRol){
        this.router.navigate(['employees']);  
      }
      if(!this.employeeRol.usuarios){
        this.router.navigate(['employees/panel']);
        return;
      }
      if(!this.idRestaurant){
        this.router.navigate(['']);
        }
    }
    this.listOfEmployees();
  }

  public results = [...this.listEmployees];

  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    this.results = this.listEmployees.filter((d) =>
      d.nombre!.toLowerCase().includes(query)
    );
  }

  listOfEmployees() {
    if (this.idRestaurant) {
      this.userService.getListEmployeeRestaurant(this.idRestaurant).subscribe({
        next: (data) => {
          this.listEmployees = data;
          this.results = [...this.listEmployees];
        },
        error: (err) => {
          this.alertService.show('Error', '❌ Error al cargar empleados', 'error');
        },
      });
    } else {
      this.userService.getListEmployeeCompany(this.idCompany).subscribe({
        next: (data) => {
          this.listEmployees = data;
          this.results = [...this.listEmployees];
        },
        error: (err) => {
          this.alertService.show('Error', '❌ Error al cargar empleados', 'error');
        },
      });
    }
  }

  listOfRoles() {
    this.roleService
      .getRolesCompany(
        !this.idCompany ? this.employeeRol.idEmpresa! : this.idCompany
      )
      .subscribe({
        next: (data) => {
          this.listRols = data;
        },
        error: (err) => {
          this.showAlert('Error', '❌ Error al cargar roles');
        },
      });
  }
  listOfRestaurants() {
    this.restaurantService
      .getListRestaurantCompany(
        !this.idCompany ? this.employeeRol.idEmpresa! : this.idCompany
      )
      .subscribe({
        next: (data) => {
          this.listRestaurants = data;
        },
        error: (err) => {
          this.showAlert('Error', '❌ Error al cargar restaurantes');
        },
      });
  }
  showCreate() {
    this.employeeForm.patchValue({
      nombre: '',
      rol: '',
      restaurant: '',
      pin: '',
    });
    this.showEmployeeModal = true;
    this.createEmployee = true;
    this.isDisabled = false;
    this.showRestaurantModal=false;
  }
  showCreateRestaurant() {
    this.restaurantForm.patchValue({
      restaurant: '',
    });
    this.showEmployeeModal = false;
    this.createEmployee = false;
    this.isDisabled = false;
    this.showRestaurantModal=true;
    this.getAvailableRestaurants();
  }

  showEdit(id: number) {
    this.getEmployee(id);
    this.createEmployee = false;
    this.isDisabled = false;
    this.showRestaurantModal=false;
  }

  showInfo(id: number) {
    this.getEmployee(id);
    this.createEmployee = false;
    this.isDisabled = true;
    this.showRestaurantModal=false;
  }

  saveEmployee() {
    let newEmployee = new Employee({
      nombre: this.employeeForm.value.nombre,
      pin: this.employeeForm.value.pin,
      idRol: this.employeeForm.value.rol,
      idEmpresa: !this.idCompany ? this.employeeRol.idEmpresa! : this.idCompany,
    });
    let idRestaurantForm = this.employeeForm.value.restaurant;
    if (this.createEmployee) {
      this.userService
        .postEmployee(
          newEmployee,
          this.idRestaurant ? this.idRestaurant! : idRestaurantForm
        )
        .subscribe({
          next: (data) => {
            this.showEmployeeModal = false;
            this.listOfEmployees();
            if (data.id && this.idRestaurant) {
              this.userService
                .postEmployeeRestaurant(data.id!, this.idRestaurant)
                .subscribe({
                  next: (da) => {
                    this.listOfEmployees();
                    this.showEmployeeModal = false;
                  },
                  error: (err) => {
                    this.alertService.show('Error', 'Error al crear la relación', 'error');
                  },
                });
            }
          },
          error: (err) => {
            this.alertService.show('Error', 'Error al crear el empleado', 'error');
          },
        });
    }

    if (!this.createEmployee) {
      newEmployee.id = this.employee.id;
      this.userService.putEmployee(newEmployee).subscribe({
        next: (data) => {
          this.showEmployeeModal = false;
          this.listOfEmployees();
        },
        error: (err) => {
          this.alertService.show('Error', 'Error al actualizar el empleado', 'error');
        },
      });
    }
  }

  cancel() {
    this.showEmployeeModal = false;
    this.showRestaurantModal=false;
  }
  cancelRestaurant(){
    this.showEmployeeModal = true;
    this.showRestaurantModal=false;
  }

  deleteEmployee() {
    let idEmpleado = this.employee.id;
    this.userService.deleteEmployee(idEmpleado!).subscribe({
      next: (data) => {
        this.listOfEmployees();
        this.showEmployeeModal = false;
        this.alertService.show('Empleado eliminado', 'Se ha eliminado correctamente', 'success');
      },
      error: (err) => {
         this.alertService.show('Error', 'Error al borrar el empleado', 'error');
      },
    });
  }
  deleteRestaurant(idRestaurant: number) {
    this.userService
      .deleteEmployeeRestaurant(this.employee.id!, idRestaurant)
      .subscribe({
        next: () => {
          this.getRestaurants();
        },
        error: (err) => {
          this.alertService.show('Error', 'No se ha podido borrar el usuario del restaurante', 'error');
        },
      });
  }

  getEmployee(id: number) {
    this.userService.getEmployee(id).subscribe({
      next: (data) => {
        this.employee = data;
        this.showEmployeeModal = true;
        this.employeeForm.patchValue({
          nombre: this.employee.nombre,
          pin: this.employee.pin,
          rol: this.employee.idRol,
        });
        this.getRestaurants();
      },
      error: (err) => {
        this.showAlert('Error', '❌ Error al cargar empleado');
      },
    });
  }
  getRestaurants() {
    this.userService.getListEmployeeRestaurants(this.employee.idEmpresa!, this.employee.id!)
      .subscribe({
        next: (data) => {
          this.listEmployeeRestaurant = data;
        },
        error: (err) => {
          this.showAlert('Error', '❌ Error al cargar los restaurantes del empleado');
        },
      });
  }
  getAvailableRestaurants() {
    const assignedIds = this.listEmployeeRestaurant.map(r => r.id);
    this.listAvaibleRestaurants = this.listRestaurants.filter(
      restaurant => !assignedIds.includes(restaurant.id)
    );
  }

  saveRestaurant() {

    let idRestaurantForm = this.restaurantForm.value.restaurant;

    this.userService
      .postEmployeeRestaurantRelation(this.employee.id!, idRestaurantForm)
      .subscribe({
        next: (data) => {
          this.showAlert('Éxito', 'Restaurante agregado con éxito');
          this.showRestaurantModal = false;
          this.showEmployeeModal = false;
        },
        error: (err) => {
          this.showAlert('Error', '❌ Error al agregar el restaurante al empleado');
        },
      });
  }

  // Método para mostrar alertas de Ionic
  async showAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header,
      message,
      buttons: ['OK'],
    });

    await alert.present();
  }

}
