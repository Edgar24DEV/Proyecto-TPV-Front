import { Component, OnInit } from '@angular/core';
import {
  IonCard,
  IonHeader,
  IonToolbar,
  IonTitle,
} from '@ionic/angular/standalone';
import { IonicModule } from '@ionic/angular';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { AppComponent } from 'src/app/app.component';

@Component({
  selector: 'app-employee-panel',
  templateUrl: './employee-panel.component.html',
  styleUrls: ['./employee-panel.component.scss'],
  imports: [IonicModule, CommonModule],
})
export class EmployeePanelComponent implements OnInit {
  constructor(private router: Router, private appComponent: AppComponent) {}
  employeeRol!: EmployeeRol;

  ngOnInit() {
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }
    this.appComponent.reloadHeader();

  }
  ionViewWillEnter() {
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }
    this.appComponent.reloadHeader();
  }

  menuItems = [
    { label: 'USUARIOS', icon: 'assets/icon/user.svg', route: '/usuarios' },
    {
      label: 'PRODUCTOS',
      icon: 'assets/icon/product.svg',
      route: '/productos',
    },
    { label: 'MESAS', icon: 'assets/icon/table.svg', route: '/mesas' },
    {
      label: 'CATEGORÍAS',
      icon: 'assets/icon/category.svg',
      route: '/categorias',
    },
    { label: 'PEDIDOS', icon: 'assets/icon/order.svg', route: '/pedidos' },
    { label: 'TPV', icon: 'assets/icon/tpv.svg', route: '/tpv' },
  ];

  tpv() {
    this.router.navigate(['restaurant/tables']);
  }
  order() {
    this.router.navigate(['order-admin']);
  }
  table() {
    this.router.navigate(['tables-admin']);
  }
  products() {
    this.router.navigate(['product-admin']);
  }
  users() {
    this.router.navigate(['user-admin']);
  }

  clients() {
    this.router.navigate(['client-admin']);
  }

  category() {
    this.router.navigate(['category-admin']);
  }
  navigate(route: string) {
    this.router.navigate([route]);
  }
}
