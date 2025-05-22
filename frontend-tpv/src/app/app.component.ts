import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { IonApp, IonRouterOutlet, MenuController } from '@ionic/angular/standalone';
import { HeaderTpvComponent } from './components/header-tpv/header-tpv.component';
import { OnInit } from '@angular/core';
import { EmployeeRol } from './models/Employee-rol';
import { AlertComponent } from './components/alert/alert.component';

@Component({
  selector: 'app-root',
  standalone:true,
  templateUrl: 'app.component.html',
  imports: [IonApp, IonRouterOutlet, HeaderTpvComponent, AlertComponent],
})
export class AppComponent implements OnInit {
  public idCompany: number = 0;
  public companyName?: string;
  public razonSocial?: string;
  public idRestaurant: number = 0;
  public restaurantName?: string;
  public employeeRol?: EmployeeRol;


  constructor(private router: Router, private menuCtrl: MenuController) {}

  ngOnInit(): void {
    this.loadInitialData();
  }

  ionViewWillEnter(){
    this.loadInitialData();
  }

  async closeAppMenu() {
    await this.menuCtrl.close('first');
  }

  private loadInitialData() {
    this.idCompany = Number(localStorage.getItem('idCompany') || '0');
    this.companyName = localStorage.getItem('company') || undefined;
    this.razonSocial = localStorage.getItem('razonSocial') || undefined;
    this.idRestaurant = Number(localStorage.getItem('idRestaurant') || '0');
    this.restaurantName = localStorage.getItem('restaurant') || undefined;
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    this.employeeRol = storedEmployeeRol ? EmployeeRol.fromJSON(JSON.parse(storedEmployeeRol)) : undefined;

  }

  reloadHeader() {
    this.loadInitialData();
  }

  logoutGlobal() {
    localStorage.clear();
    this.reloadHeader();
    this.router.navigate(['']);
  }
}