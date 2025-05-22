import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { IonicModule, IonMenuButton, IonButton } from '@ionic/angular'; // Importa IonMenuButton e IonButton
import { CommonModule } from '@angular/common';
import { Restaurant } from 'src/app/models/Restaurant';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { AppComponent } from 'src/app/app.component';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-list-restaurant-company',
  templateUrl: './list-restaurant-company.component.html',
  styleUrls: ['./list-restaurant-company.component.scss'],
  standalone: true, // Se indica que el componente es independiente
  imports: [
    IonicModule,
    CommonModule,
    HeaderTpvComponent,
    ReactiveFormsModule, // Asegúrate de importar ReactiveFormsModule aquí
  ],
})
export class ListRestaurantCompanyComponent implements OnInit {
  idCompany!: number;
  listRestaurants: Restaurant[] = [];
  companyName!: string;
  showRestaurantModal: boolean = false;
  restaurantForm: FormGroup;
  editRestaurantModal: boolean = false;
  idRestaurant: number = 0;
  isDisabled: boolean = false;
  createRestaurant: boolean = false;
  formSubmitted: boolean = false;

  constructor(
    private router: Router,
    private rs: RestaurantService,
    private fb: FormBuilder,
    private appComponent: AppComponent,
    private alertService: AlertService
  ) {
    this.restaurantForm = this.fb.group({
      nombre: ['', Validators.required],
      direccion: ['', Validators.required],
      telefono: [
        '',
        [Validators.required, Validators.pattern(/^[0-9\s\+\-]{7,20}$/)],
      ],
      contrasenya: [
        '',
        [
          Validators.required,
          Validators.pattern(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{6,50}$/),
        ],
      ],
      direccionFiscal: ['', Validators.required],
      cif: [
        '',
        [Validators.required, Validators.pattern(/^[A-Za-z0-9]{8,12}$/)],
      ],
      razonSocial: ['', Validators.required],
    });
  }

  ngOnInit() {
    const storedCompanyId = localStorage.getItem('idCompany');
    if (!storedCompanyId) {
      this.router.navigate(['/loginCompany']);
    }
    const storedCompanyName = localStorage.getItem('company');
    if (storedCompanyName) {
      this.companyName = storedCompanyName;
    }
    this.idCompany = Number(storedCompanyId);
    this.listOfRestaurants();
  }

  ionViewWillEnter() {
    const storedCompanyId = localStorage.getItem('idCompany');
    if (!storedCompanyId) {
      this.router.navigate(['/loginCompany']);
    }
    const storedCompanyName = localStorage.getItem('company');
    if (storedCompanyName) {
      this.companyName = storedCompanyName;
    }
    this.idCompany = Number(storedCompanyId);
    this.listOfRestaurants();
  }

  listOfRestaurants() {
    if (this.idCompany > 0) {
      this.rs.getListRestaurantCompany(this.idCompany).subscribe({
        next: (data) => {
          this.listRestaurants = data;
        },
        error: (err) => {
          this.router.navigate(['/']);
        },
      });
    }
  }

  restaurantLogin(restaurant: Restaurant): void {
    localStorage.setItem("restaurant", restaurant.nombre!);
    localStorage.setItem("idRestaurant", String(restaurant.id));
    localStorage.removeItem('idCompany');
    localStorage.removeItem('company');
    this.appComponent.reloadHeader();
    this.router.navigate(['employees']);
  }

  logout() {
    localStorage.clear();
    this.router.navigate(['loginCompany']);
  }

  addRestaurant() {
    this.restaurantForm.reset();
    this.showRestaurantModal = true;
  }

  saveRestaurant() {
    const formData = this.restaurantForm.value;
    this.rs.postRestaurant(formData, this.idCompany).subscribe({
      next: (data) => {
        this.cancel();
        this.listOfRestaurants();
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudo crear el restaurante correctamente', 'error');
      },
    });
  }

  editRestaurant(restaurant: Restaurant) {
    this.isDisabled = false;
    this.createRestaurant = false;
    this.restaurantForm.patchValue({
      nombre: restaurant.nombre,
      direccion: restaurant.direccion,
      telefono: restaurant.telefono,
      direccionFiscal: restaurant.direccionFiscal,
      cif: restaurant.cif,
      razonSocial: restaurant.razonSocial,
    });

    this.idRestaurant = restaurant.id!;
    this.editRestaurantModal = true;
  }

  updateRestaurant() {
    const formData = this.restaurantForm.value;
    this.rs
      .putRestaurant(formData, this.idCompany, this.idRestaurant)
      .subscribe({
        next: (data) => {
          this.cancel();
          this.listOfRestaurants();
        },
        error: (err) => {
          this.alertService.show('Error', 'No se pudo actualizar el restaurante correctamente', 'error');
        },
      });
  }

  cancel() {
    this.editRestaurantModal = false;
  }

  darBajaRestaurante() {
    this.rs.deleteRestaurant(this.idRestaurant).subscribe({
      next: (data) => {
        this.listOfRestaurants();
      },
      error: () => {
        this.alertService.show('Error', 'No se pudo eliminar el restaurante correctamente', 'error');
      }
    });
  }

  isInvalid(controlName: string): boolean {
    const control = this.restaurantForm.get(controlName);
    return !!(control && control.invalid && (control.touched || this.formSubmitted));
  }
}
