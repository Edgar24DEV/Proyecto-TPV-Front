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
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { Table } from 'src/app/models/Table';
import { TableService } from 'src/app/services/table.service';
import { LocationService } from 'src/app/services/location.service';
import { Location } from 'src/app/models/Location';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  selector: 'app-mesas-admin',
  templateUrl: './mesas-admin.component.html',
  styleUrls: ['./mesas-admin.component.scss'],
  imports: [ IonicModule, CommonModule, ReactiveFormsModule],
})
export class MesasAdminComponent implements OnInit {
  tableForm!: FormGroup;
  locationForm!: FormGroup;
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  listTables: Table[] = [];
  table!: Table;
  isDisabledTable: boolean = false;
  showTableModal: boolean = false;
  createTable: boolean = false;
  resultsTable: Table[] = [];
  formSubmittedTable: boolean = false;

  listLocation: Location[] = [];
  location!: Location;
  isDisabledLocation: boolean = false;
  showLocationModal: boolean = false;
  createLocation: boolean = false;
  resultsLocation: Location[] = [];
  formSubmittedLocation: boolean = false;

  constructor(
    private fb: FormBuilder,
    private ts: TableService,
    private ls: LocationService,
    private router: Router,
    private alertService: AlertService
  ) {}

  ngOnInit() {
    const storedRestaurant = localStorage.getItem('idRestaurant');

    if (storedRestaurant) {
      this.idRestaurant = Number(storedRestaurant);
    }
    const storedEmployeeRol = localStorage.getItem('employeeRol');
    if (storedEmployeeRol) {
      const plain = JSON.parse(storedEmployeeRol);
      this.employeeRol = EmployeeRol.fromJSON(plain);
      this.listOfTable();
      this.listOfLocations();
    }

    const storedCompany = localStorage.getItem('idCompany');
    if (storedCompany) {
      this.idCompany = Number(storedCompany);
    }

    if(!this.idCompany || this.idRestaurant){
      if(!this.employeeRol){
        this.router.navigate(['employees']);  
      }
      if(!this.employeeRol.mesas){
        this.router.navigate(['employees/panel']);
        return;
      }
      if(!this.idRestaurant){
        this.router.navigate(['']);
        }
    }

    this.tableForm = this.fb.group({
      mesa: ['', [Validators.required, Validators.maxLength(255)]],
      activo: [true, Validators.required],
      idUbicacion: [null, Validators.required],
    });
    this.locationForm = this.fb.group({
      ubicacion: ['', [Validators.required, Validators.maxLength(255)]],
      activo: [true, Validators.required],
    });
  }

  listOfTable() {
    this.ts.getListTableRestaurant(this.idRestaurant!).subscribe({
      next: (data) => {
        this.listTables = data;
        this.resultsTable = this.listTables;
      },
      error: (e) => {
        this.alertService.show('Error', 'Error no se pudieron cargar las mesas.', 'error');
      },
    });
  }

  listOfLocations() {
    this.ls.getListLocationRestaurant(this.idRestaurant!).subscribe({
      next: (data) => {
        this.listLocation = data;
        this.resultsLocation = this.listLocation;
      },
      error: (e) => {
        this.alertService.show('Error', 'Error no se pudieron cargar las ubicaciones.', 'error');
      },
    });
  }
  handleInputTable(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    this.resultsTable = this.listTables.filter((d) =>
      d.mesa!.toLowerCase().includes(query)
    );
  }
  handleInputLocation(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';

    this.resultsLocation = this.listLocation.filter((location) =>
      location.ubicacion!.toLowerCase().includes(query)
    );

    const filteredLocationIds = this.resultsLocation.map((loc) => loc.id);

    this.resultsTable = this.listTables.filter((table) =>
      filteredLocationIds.includes(table.idUbicacion!)
    );
  }
  getTable(id: number) {
    this.ts.findByIdTable(id).subscribe({
      next: (data) => {
        this.table = data;
        this.tableForm.patchValue({
          mesa: data.mesa,
          activo: data.activo,
          idUbicacion: data.idUbicacion
        });
      },
      error(err) {
        console.error('Error al cargar el mesa', err);
      },
    });
  }
  getLocation(id: number) {
    this.ls.findByIdLocation(id).subscribe({
      next: (data) => {
        this.location = data;
        this.locationForm.patchValue({
          ubicacion: data.ubicacion,
          activo: data.activo,
        });
      },
      error(err) {
        console.error('Error al cargar la ubicación ', err);
      },
    });
  }
  showCreateTable() {
    this.showTableModal = true;
    this.createTable = true;
    this.isDisabledTable = false;
    this.tableForm.patchValue({
      mesa: '',
      activo: true,
      idUbicacion: '',
    });
  }

  showEditTable(id: number) {
    this.showTableModal = true;
    this.getTable(id);
    this.createTable = false;
    this.isDisabledTable = false;
  }

  showInfoTable(id: number) {
    this.showTableModal = true;
    this.getTable(id);
    this.createTable = false;
    this.isDisabledTable = true;
  }
  showCreateLocation() {
    this.showLocationModal = true;
    this.createLocation = true;
    this.isDisabledLocation = false;
    this.locationForm.patchValue({
      ubicacion: '',
    });
  }

  showEditLocation(id: number) {
    this.showLocationModal = true;
    this.getLocation(id);
    this.createLocation = false;
    this.isDisabledLocation = false;
  }

  showInfoLocation(id: number) {
    this.showLocationModal = true;
    this.getLocation(id);
    this.createLocation = false;
    this.isDisabledLocation = true;
  }
  cancel() {
    this.showTableModal = false;
    this.showLocationModal = false;
  }
  onToggleChange(event: CustomEvent) {
    const isActive = event.detail.checked;
  }

  isInvalidTable(controlName: string): boolean {
    const control = this.tableForm.get(controlName);
    return !!(
      control &&
      control.invalid &&
      (control.touched || this.formSubmittedTable)
    );
  }
  isInvalidLocation(controlName: string): boolean {
    const control = this.locationForm.get(controlName);
    return !!(
      control &&
      control.invalid &&
      (control.touched || this.formSubmittedLocation)
    );
  }

  saveTable() {
    this.formSubmittedTable = true;
    if (this.tableForm.invalid) {
      this.tableForm.markAllAsTouched();
      return;
    }
    let newTable = new Table({
      mesa: this.tableForm.value.mesa,
      activo: this.tableForm.value.activo,
      idUbicacion: this.tableForm.value.idUbicacion,
    });

    if(this.createTable){
      this.ts.addTable(newTable).subscribe(
        {
          next:(data)=>{
            this.showTableModal=false;
            this.listOfTable();
          },
          error(err) {
            console.error("Error al crear una mesa " , err);
          },
        });
    }

    if(!this.createTable){
      newTable.id= this.table.id;
      this.ts.updateTable(newTable).subscribe(
        {
          next:(data)=>{
            this.showTableModal=false;
            this.listOfTable();
          },
          error(err) {
            console.error("Error al actualizar una mesa " , err);
          },
        });
    }
  }
  deleteTable(){
    let idMesa = this.table.id;
    this.ts.deleteTable(idMesa!).subscribe({
      next:(data) => {
        this.listOfTable();
        this.showTableModal = false;
      },
        error: (err) => {
          this.alertService.show('Error', 'Error no se pudo eliminar la mesa.', 'error');
        },
      });
  }
  deleteLocation(){

    let idLocation = this.location.id;
    this.ls.deleteLocation(idLocation!).subscribe({
      next:(data) => {
        this.listOfLocations();
        this.showTableModal = false;
        this.showLocationModal = false;
      },
        error: (err) => {
          this.alertService.show('Error', 'Error no se pudo eliminar la ubicación.', 'error');
        },
      });
  }
  saveLocation(){
    this.formSubmittedLocation = true;
    if (this.locationForm.invalid) {
      this.locationForm.markAllAsTouched();
      return;
    }
    let newLocation = new Location({
      ubicacion: this.locationForm.value.ubicacion,
      activo: this.locationForm.value.activo,
      idRestaurante:this.idRestaurant,
    })
    if(this.createLocation){
      this.ls.addLocation(newLocation).subscribe(
        {
          next:(data)=>{
            this.showLocationModal=false;
            this.listOfLocations();
          },
          error(err) {
            console.error("Error al crear una ubicación " , err);
          },
        });
    }

    if(!this.createLocation){
      newLocation.id= this.location.id;
      this.ls.updateLocation(newLocation).subscribe(
        {
          next:(data)=>{
            this.showLocationModal=false;
            this.listOfLocations();
            this.listOfTable();
          },
          error(err) {
            console.error("Error al actualizar una ubicación " , err);
          },
        });
    }
  }


}
