import { Routes } from '@angular/router';
import { LoginRestaurantComponent } from './components/login-restaurant/login-restaurant.component';
import { ListEmployeeRestaurantComponent } from './components/list-employee-restaurant/list-employee-restaurant.component';
import { EmployeePanelComponent } from './components/employee-panel/employee-panel.component';
import { ListTableRestaurantComponent } from './components/list-table-restaurant/list-table-restaurant.component';
import { TpvComponent } from './components/tpv/tpv.component';
import { ProductsListComponent } from './components/products-list/products-list.component';
import { CategoriesListComponent } from './components/categories-list/categories-list.component';
import { OrderLineListComponent } from './components/order-line-list/order-line-list.component';
import { SelectLoginComponent } from './components/select-login/select-login.component';
import { PaymentComponent } from './components/payments/payments.component';
import { LoginCompanyComponent } from './components/login-company/login-company.component';
import { ListRestaurantCompanyComponent } from './components/list-restaurant-company/list-restaurant-company.component';
import { UsersAdminComponent } from './components/users-admin/users-admin.component';
import { ProductAdminComponent } from './components/product-admin/product-admin.component';
import { OrdersAdminComponent } from './components/orders-admin/orders-admin.component';
import { ClientAdminComponent } from './components/client-admin/client-admin.component';
import { CategoryAdminComponent } from './components/category-admin/category-admin.component';
import { MesasAdminComponent } from './components/mesas-admin/mesas-admin.component';
import { RoleAdminComponent } from './components/role-admin/role-admin.component';

export const routes: Routes = [
  {
    path: 'home',
    loadComponent: () => import('./home/home.page').then((m) => m.HomePage),
  },
  {
    path: '',
    redirectTo: 'select-login',
    pathMatch: 'full',
  },
  {
    path:'select-login',
    component: SelectLoginComponent,
  },
  {
    path: 'loginRestaurant',
    component: LoginRestaurantComponent,
  },
  {
    path: 'loginCompany',
    component: LoginCompanyComponent,
  },
  {
    path: 'employees',
    component: ListEmployeeRestaurantComponent,
  },
  {
    path: 'restaurants',
    component: ListRestaurantCompanyComponent,
  },
  {
    path: 'employees/panel',
    component: EmployeePanelComponent,
  },
  {
    path: 'restaurant/tables',
    component: ListTableRestaurantComponent,
  },
  {
    path: 'tpv',
    component: TpvComponent,
  },
  {
    path: 'products',
    component: ProductsListComponent,
  },
  {
    path:'categories',
    component: CategoriesListComponent,
  },
  {
    path:'order-lines',
    component: OrderLineListComponent,
  },
  {
    path:'payment',
    component: PaymentComponent,
  },
  {
    path:'user-admin',
    component: UsersAdminComponent,
  },  {
    path:'product-admin',
    component: ProductAdminComponent,
  }, {
    path:'order-admin',
    component:OrdersAdminComponent,
  
  },  
  {
    path:'client-admin',
    component: ClientAdminComponent,
  },
  {
    path:'category-admin',
    component: CategoryAdminComponent,
  },
  {
    path:'tables-admin',
    component: MesasAdminComponent,
  },
  {
    path:'roles-admin',
    component: RoleAdminComponent,
  },
];
