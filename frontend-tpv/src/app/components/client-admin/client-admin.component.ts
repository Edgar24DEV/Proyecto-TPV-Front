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
import { Client } from 'src/app/models/Client';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { ClientService } from 'src/app/services/client.service';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-client-admin',
  templateUrl: './client-admin.component.html',
  styleUrls: ['./client-admin.component.scss'],
  imports: [ IonicModule, CommonModule, ReactiveFormsModule],
})
export class ClientAdminComponent  implements OnInit {
  clientForm!: FormGroup;
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  listClients: Client[] = [];
  client!: Client;
  isDisabled: boolean = false;
  showClientModal: boolean = false;
  createClient: boolean = false;
  results: Client[] = [];
  formSubmitted: boolean = false;
  companyName!: string;

  constructor(
    private fb: FormBuilder,
    private cS: ClientService,
    private router: Router,
    private alertService: AlertService
  ) { }

  ngOnInit() {
    this.initializeData();

    this.clientForm = this.fb.group({
      razonSocial: ['', [Validators.required, Validators.maxLength(255)]],
      cif: ['', [Validators.required, Validators.pattern(/^[ABCDEFGHJKLMNPQRSUVW]\d{7}[0-9A-J]$/i)]],
      direccion: ['', [Validators.required, Validators.maxLength(255)]],
      email: ['', [Validators.required, Validators.email, Validators.maxLength(255)]],
    });
  }

  ionViewWillEnter() {
    this.initializeData();
  }

  private initializeData() {
    const storedCompany = localStorage.getItem('idCompany');
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
      this.companyName = localStorage.getItem('company')!;
    }

    const storedRestaurant = localStorage.getItem('idRestaurant');
    if (storedRestaurant && !storedCompany) {
      this.idRestaurant = Number(storedRestaurant);
    }

    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
    }

    if(!this.idCompany || this.idRestaurant){
      if(!this.employeeRol){
        this.router.navigate(['employees']);  
      }
      if(!this.employeeRol.clientes){
        this.router.navigate(['employees/panel']);
        return;
      }
      if(!this.idRestaurant){
        this.router.navigate(['']);
        }
    }
  
    this.listOfClients();
  }

  listOfClients() {
    this.cS.getListClientsCompany((!this.idCompany) ? this.employeeRol.idEmpresa! : this.idCompany).subscribe({
      next: (data) => {
        this.listClients = data;
        this.results = data;
      },
      error: (e) => {
        // Handle the error
      }
    });
  }

  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    this.results = this.listClients.filter((d) => d.razonSocial!.toLowerCase().includes(query));
  }

  showCreate() {
    this.showClientModal = true;
    this.createClient = true;
    this.isDisabled = false;
    this.clientForm.patchValue({
      razonSocial: "",
      cif: "",
      direccion: "",
      email: "",
    });
  }

  showEdit(id: number) {
    this.showClientModal = true;
    this.getClient(id);
    this.createClient = false;
    this.isDisabled = false;
  }

  showInfo(id: number) {
    this.showClientModal = true;
    this.getClient(id);
    this.createClient = false;
    this.isDisabled = true;
  }

  cancel() {
    this.showClientModal = false;
  }

  getClient(id: number) {
    this.cS.findByIdClient(id).subscribe({
      next: (data) => {
        this.client = data;
        this.clientForm.patchValue({
          razonSocial: data.razonSocial,
          cif: data.cif,
          direccion: data.direccion,
          email: data.email,
        });
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudo encontrar el cliente', 'error');
      },
    });
  }

  saveClient() {
    this.formSubmitted = true;
    if (this.clientForm.invalid) {
      this.clientForm.markAllAsTouched();
      return;
    }

    let newClient = new Client({
      razonSocial: this.clientForm.value.razonSocial,
      cif: this.clientForm.value.cif.toUpperCase(),
      direccion: this.clientForm.value.direccion,
      email: this.clientForm.value.email,
    });

    if (this.createClient) {
      this.cS.addClient(newClient, (!this.idCompany) ? this.employeeRol.idEmpresa! : this.idCompany).subscribe(
        {
          next: (data) => {
            this.showClientModal = false;
            this.listOfClients();
          },
          error: (err) => {
            this.alertService.show('Error', 'No se pudo crear el cliente correctamente', 'error');
          },
        });
    }
    if (!this.createClient) {
      newClient.id = this.client.id;
      this.cS.updateClient(newClient, (!this.idCompany) ? this.employeeRol.idEmpresa! : this.idCompany).subscribe(
        {
          next: (data) => {
            this.showClientModal = false;
            this.listOfClients();
          },
          error: (err) => {
            this.alertService.show('Error', 'No se pudo actualizar el cliente correctamente', 'error');
          },
        });
    }
  }

  delete() {
    let idCliente = this.client.id;
    this.cS.deleteClient(idCliente!).subscribe({
      next: () => {
        this.listOfClients();
        this.showClientModal = false;
      },
      error: (err) => {
        this.alertService.show('Error', 'No se pudo eliminar el cliente correctamente', 'error');
      },
    });
  }

  isInvalid(controlName: string): boolean {
    const control = this.clientForm.get(controlName);
    return !!(control && control.invalid && (control.touched || this.formSubmitted));
  }
}
