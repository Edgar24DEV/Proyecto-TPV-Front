import { Component, Input, OnInit, OnChanges, SimpleChanges, ChangeDetectionStrategy, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonicModule, MenuController } from '@ionic/angular';
import { Router } from '@angular/router';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { AppComponent } from 'src/app/app.component';

@Component({
  selector: 'app-header-tpv',
  standalone: true,
  templateUrl: './header-tpv.component.html',
  styleUrls: ['./header-tpv.component.scss'],
  imports: [IonicModule, CommonModule],
  changeDetection: ChangeDetectionStrategy.OnPush
})
export class HeaderTpvComponent implements OnInit, OnChanges {
  @Input() restaurantName?: string;
  @Input() idCompany!: number;
  @Input() idRestaurant!: number;
  @Input() companyName?: string;
  @Input() razonSocial?: string;
  @Input() employeeRol?: EmployeeRol;
  @Input() reloadHeader!: () => void;


  isCompanyLoggedIn: boolean = false;

  constructor(
    private router: Router,
    private menu: MenuController,
    private cdr: ChangeDetectorRef,
    private appComponent: AppComponent
  ) {}

  ngOnInit() {
    this.checkCompanyLogin();
  }

  ionViewWillEnter(){
    this.checkCompanyLogin();
  }

  ngOnChanges(changes: SimpleChanges): void { // Implementa ngOnChanges
    if (changes['idCompany']) {
      this.isCompanyLoggedIn = !!changes['idCompany'].currentValue;
    }
    if (changes['restaurantName']) {
    }
    if (changes['employeeRol']) {
    }
    this.cdr.detectChanges(); // Asegúrate de detectar los cambios en la vista
  }

  checkCompanyLogin() {
    this.isCompanyLoggedIn = !!this.idCompany;
    this.cdr.detectChanges();
  }


  async logoutApp() {
    await this.appComponent.closeAppMenu();
    localStorage.clear();
    this.router.navigate(['']);
  }

  logoutEmployee() {
    this.menu.close('first');
    localStorage.removeItem('employeeRol');
    this.employeeRol = undefined;
    this.router.navigate(['employees']);
  }

  async goHome(){
    await this.appComponent.closeAppMenu();
    this.router.navigate(['/restaurants']);
  }

  async goPanel() {
    this.router.navigate(['/employees/panel']);
  }

  async goTables() {
    this.router.navigate(['/restaurant/tables']);
  }

  async goOrder() {
    this.router.navigate(['/restaurant/tables']);
  }

  async products() {
    await this.appComponent.closeAppMenu();
    this.cdr.detectChanges();
    localStorage.removeItem('idRestaurant');
    this.router.navigate(['product-admin']);
  }

  async users() {
    await this.appComponent.closeAppMenu();
    this.router.navigate(['user-admin']);
  }

  async clients() {
    await this.appComponent.closeAppMenu();
    this.router.navigate(['client-admin']);
  }

  async orders() {
    await this.appComponent.closeAppMenu();
    this.cdr.detectChanges();
    this.router.navigate(['order-admin']);
  }

  async category() {
    await this.appComponent.closeAppMenu();
    this.cdr.detectChanges();
    this.router.navigate(['category-admin']);
  }

  async roles() {
    await this.appComponent.closeAppMenu();
    this.router.navigate(['roles-admin']);
  }

  async logoutRestaurant() {
    localStorage.clear();
    this.reloadHeader();
    this.router.navigate(['']);
  }
}
