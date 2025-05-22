import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { Restaurant } from 'src/app/models/Restaurant';

import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { AppComponent } from 'src/app/app.component'; // Importa AppComponent

@Component({
  selector: 'app-login-restaurant',
  standalone: true,
  templateUrl: './login-restaurant.component.html',
  styleUrls: ['./login-restaurant.component.scss'],
  imports: [IonicModule, FormsModule, ReactiveFormsModule, CommonModule],
})
export class LoginRestaurantComponent implements OnInit {
  restaurant: Restaurant | null = null;
  loginRestaurantForm: FormGroup;
  mensajeError = false;

  constructor(
    private rs: RestaurantService,
    private fb: FormBuilder,
    private router: Router,
    private appComponent: AppComponent
  ) {
    this.loginRestaurantForm = this.fb.group({
      nombre: new FormControl('', [Validators.required]),
      contrasenya: new FormControl('', [Validators.required])
    });
  }

  ngOnInit() {
    localStorage.clear();
    this.appComponent.reloadHeader();
  }

  ionViewWillEnter(){
    localStorage.clear();
    this.appComponent.reloadHeader();
  }

  loginRestaurant(): void {
    if (this.loginRestaurantForm.valid) {
      const { nombre, contrasenya } = this.loginRestaurantForm.value;

      this.rs.postLoginRestaurant(nombre, contrasenya).subscribe({
        next: (restaurant: Restaurant) => {
          this.restaurant = restaurant;
          this.mensajeError = false;
          if (this.restaurant.nombre) {
            localStorage.setItem("restaurant", this.restaurant.nombre);
          }
          if (this.restaurant.id) {
            localStorage.setItem("idRestaurant", String(this.restaurant.id));
          }
          if (localStorage.getItem('idCompany')) {
            localStorage.removeItem('idCompany');
          }
          this.appComponent.reloadHeader(); // Llama a reloadHeader() aquí
          this.router.navigate(['/employees']);
        },
        error: (error) => {
          this.mensajeError = true;
        }
      });
    } else {
      console.log('Formulario no válido');
    }
  }
  volver() {
    this.router.navigate(['select-login']);
  }
}
