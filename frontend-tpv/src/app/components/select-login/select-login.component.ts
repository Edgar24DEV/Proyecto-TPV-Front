import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators, ReactiveFormsModule, FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { Restaurant } from 'src/app/models/Restaurant';

import { CommonModule } from '@angular/common';
import { Router, RouterLink } from '@angular/router';
import { RestaurantService } from 'src/app/services/restaurant.service';
import { AppComponent } from 'src/app/app.component';

@Component({
  selector: 'app-select-login',
  templateUrl: './select-login.component.html',
  styleUrls: ['./select-login.component.scss'],
  imports: [IonicModule, FormsModule, ReactiveFormsModule, CommonModule],
})
export class SelectLoginComponent  implements OnInit {
  restaurant: Restaurant | null = null;
  mensajeError = false;

  constructor( 
    private rs: RestaurantService, 
    private fb: FormBuilder, 
    private router: Router,
    private appComponent: AppComponent
  ) {
    
  }
  ngOnInit() {
    localStorage.clear();
    this.appComponent.reloadHeader(); 
  }

  ionViewWillEnter(){
    localStorage.clear();
    this.appComponent.reloadHeader(); 
  }

  redirectRestaurant(){
    this.router.navigate(['/loginRestaurant']);
  }
  redirectAdministrate(){
    this.router.navigate(['/loginCompany']);
  }


}
