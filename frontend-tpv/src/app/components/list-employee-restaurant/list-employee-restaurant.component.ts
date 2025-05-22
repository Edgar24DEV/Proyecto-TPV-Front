import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Employee } from 'src/app/models/Employee';
import { EmployeeService } from 'src/app/services/employee.service';
import { IonicModule } from '@ionic/angular';
import { NgModel } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { IonAlert } from '@ionic/angular/standalone';
import { AlertController } from '@ionic/angular';
import { EnterPinModalComponent } from '../enter-pin-modal/enter-pin-modal.component';
import { ModalController } from '@ionic/angular';
import { AppComponent } from 'src/app/app.component';
import { EmployeeRol } from 'src/app/models/Employee-rol';

@Component({
  selector: 'app-list-employee-restaurant',
  standalone: true,
  templateUrl: './list-employee-restaurant.component.html',
  styleUrls: ['./list-employee-restaurant.component.scss'],
  imports: [ IonicModule, CommonModule],
})
export class ListEmployeeRestaurantComponent implements OnInit {
  listEmployees: Employee[] = [];
  public idCompany!: number;
  public companyName?: string;
  public idRestaurant!: number;
  public restaurantName?: string; // Declara restaurantName
  public employeeRol?: EmployeeRol; // Declara employeeRol

  constructor(
    private router: Router,
    private es: EmployeeService,
    private alertController: AlertController,
    private modalController: ModalController,
    public appComponent: AppComponent
  ) {}

  ngOnInit() {
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    if (!storedRestaurantId) {
      this.router.navigate(['/loginRestaurant']);
    }
    this.idRestaurant = Number(storedRestaurantId);
    this.restaurantName = this.appComponent.restaurantName; // Asigna el valor desde AppComponent
    this.employeeRol = this.appComponent.employeeRol;     // Asigna el valor desde AppComponent
    this.listOfEmployees();
  }

  ionViewWillEnter() {
    const storedRestaurantId = localStorage.getItem('idRestaurant');
    if (!storedRestaurantId) {
      this.router.navigate(['/loginRestaurant']);
    }
    this.idRestaurant = Number(storedRestaurantId);
    this.restaurantName = this.appComponent.restaurantName; // Asigna el valor desde AppComponent
    this.employeeRol = this.appComponent.employeeRol;     // Asigna el valor desde AppComponent
    this.listOfEmployees();
  }
  listOfEmployees() {
    this.es.getListEmployeeRestaurant(this.idRestaurant).subscribe({
      next: (data) => {
        this.listEmployees = data;
      },
      error: (err) => {
        this.router.navigate(['/loginRestaurant']);
      },
    });
  }

  public alertButtons = ['Entrar'];
  public alertInputs = [
    {
      type: 'number',
      placeholder: 'PIN',
      min: 4,
      max: 4,
    },
  ];

async presentAlert(employee: Employee) {
    const alert = await this.alertController.create({
      header: 'Introduce el PIN',
      inputs: [
        {
          name: 'pin',
          type: 'password',
          placeholder: 'PIN',
          attributes: {
            minlength: 4,
            maxlength: 4,
            inputmode: 'numeric',
          },
        },
      ],
      buttons: [
        { text: 'Cancelar', role: 'cancel' },
        { text: 'Entrar', handler: (data) => console.log(`PIN ingresado: ${data.pin}`) },
      ],
    });

    await alert.present();
  }

  async presentPinModal(employee: Employee) {
    if (!employee?.id) return;

    const modal = await this.modalController.create({
      component: EnterPinModalComponent,
      //cssClass: 'pin-modal',
      componentProps: {
        idEmployee: employee.id
      }
    });

      await modal.present();

  await modal.present();

  const pin = (await modal.onDidDismiss()).data;
  this.modalController.dismiss(pin); 
}
}

