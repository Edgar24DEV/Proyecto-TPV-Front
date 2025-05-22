import { TestBed, ComponentFixture, waitForAsync } from '@angular/core/testing';
import { MesasAdminComponent } from './mesas-admin.component';
import { TableService } from 'src/app/services/table.service';
import { LocationService } from 'src/app/services/location.service';
import { AlertService } from 'src/app/services/alert.service';
import { Router } from '@angular/router';
import {
  ReactiveFormsModule,
  FormsModule,
  FormGroup,
  FormControl,
  Validators,
} from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { of, throwError } from 'rxjs';
import { Table } from 'src/app/models/Table';
import { Location } from 'src/app/models/Location';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { CommonModule } from '@angular/common';

describe('MesasAdminComponent', () => {
  let component: MesasAdminComponent;
  let fixture: ComponentFixture<MesasAdminComponent>;
  let mockRouter: jasmine.SpyObj<Router>;
  let mockTableService: jasmine.SpyObj<TableService>;
  let mockLocationService: jasmine.SpyObj<LocationService>;
  let mockAlertService: jasmine.SpyObj<AlertService>;

  beforeEach(waitForAsync(() => {
  mockRouter = jasmine.createSpyObj('Router', ['navigate']);
  mockTableService = jasmine.createSpyObj('TableService', [
    'getListTableRestaurant', 'findByIdTable', 'addTable', 'updateTable', 'deleteTable',
  ]);
  mockLocationService = jasmine.createSpyObj('LocationService', [
    'getListLocationRestaurant', 'findByIdLocation', 'addLocation', 'updateLocation', 'deleteLocation',
  ]);
  mockAlertService = jasmine.createSpyObj('AlertService', ['show']);

  TestBed.configureTestingModule({
    imports: [
      IonicModule.forRoot(),
      CommonModule,
      FormsModule,
      ReactiveFormsModule,
      MesasAdminComponent,
    ],
    providers: [
      { provide: Router, useValue: mockRouter },
      { provide: TableService, useValue: mockTableService },
      { provide: LocationService, useValue: mockLocationService },
      { provide: AlertService, useValue: mockAlertService },
    ],
  }).compileComponents();

  // IMPORTANTE: fijar retornos de los métodos antes de que se usen
  mockTableService.getListTableRestaurant.and.returnValue(of([]));
  mockLocationService.getListLocationRestaurant.and.returnValue(of([]));

  fixture = TestBed.createComponent(MesasAdminComponent);
  component = fixture.componentInstance;
}));


  it('should create', () => {
    expect(component).toBeTruthy();
  });

  describe('ngOnInit', () => {
    it('should initialize with data from localStorage', () => {
      spyOn(localStorage, 'getItem').and.callFake((key) => {
        if (key === 'idRestaurant') return '1';
        if (key === 'employeeRol') return JSON.stringify({ mesas: true });
        if (key === 'idCompany') return '2';
        return null;
      });

      (mockTableService.getListTableRestaurant as jasmine.Spy).and.returnValue(
        of([])
      );
      (
        mockLocationService.getListLocationRestaurant as jasmine.Spy
      ).and.returnValue(of([]));

      component.ngOnInit();

      expect(component.idRestaurant).toBe(1);
      expect(component.idCompany).toBe(2);
      expect(mockTableService.getListTableRestaurant).toHaveBeenCalledWith(1);
      expect(
        mockLocationService.getListLocationRestaurant
      ).toHaveBeenCalledWith(1);
    });
  });

  describe('Table Management', () => {
    beforeEach(() => {
      component.idRestaurant = 1;
    });

    it('should update an existing table', () => {

      component.tableForm = new FormGroup({
      mesa: new FormControl(['', [Validators.required, Validators.maxLength(255)]]),
      activo:  new FormControl( [true, Validators.required]),
      idUbicacion: new FormControl( [null, Validators.required]),
    });
      const updatedTable = {
        mesa: 'Updated Table',
        activo: true,
        idUbicacion: 1,
      };
      component.tableForm.setValue(updatedTable);
      component.createTable = false;
      component.table = { id: 1 } as Table;

      (mockTableService.updateTable as jasmine.Spy).and.returnValue(of({}));

      component.saveTable();

      expect(mockTableService.updateTable).toHaveBeenCalledWith(
        jasmine.any(Table)
      );
      expect(component.showTableModal).toBeFalse();
    });

    it('should delete a table', () => {
      component.table = { id: 1 } as Table;
      (mockTableService.deleteTable as jasmine.Spy).and.returnValue(of({}));

      component.deleteTable();

      expect(mockTableService.deleteTable).toHaveBeenCalledWith(1);
      expect(component.showTableModal).toBeFalse();
    });
  });

  describe('Location Management', () => {
    beforeEach(() => {
      component.idRestaurant = 1;
    });

    it('should create a new location', () => {
      // Inicializa locationForm antes de usarlo
      component.locationForm = new FormGroup({
        ubicacion: new FormControl('', [Validators.required, Validators.maxLength(255)]),
        activo: new FormControl(true, Validators.required),
      });

      const newLocation = { ubicacion: 'New Location', activo: true };
      component.locationForm.setValue(newLocation);
      component.createLocation = true;

      (mockLocationService.addLocation as jasmine.Spy).and.returnValue(of({}));

      component.saveLocation();

      expect(mockLocationService.addLocation).toHaveBeenCalledWith(
        jasmine.objectContaining({ ubicacion: 'New Location', activo: true })
      );
      expect(component.showLocationModal).toBeFalse();
    });


  describe('UI Interactions', () => {
    it('should filter tables based on search input', () => {
      component.listTables = [
        {
          id: 1,
          activo: true,
          idUbicacion: 1,
          mesa: 'Mesa 1',
          pos_x: 2,
          pos_y: 3,
          estado: 'libre',
          nComensales: 0,
        },
        {
          id: 2,
          activo: false,
          idUbicacion: 1,
          mesa: 'Mesa 2',
          pos_x: 1,
          pos_y: 3,
          estado: 'libre',
          nComensales: 0,
        },
        {
          id: 3,
          activo: true,
          idUbicacion: 2,
          mesa: 'Mesa 3',
          pos_x: 2,
          pos_y: 1,
          estado: 'libre',
          nComensales: 0,
        },
      ];

      const event = { target: { value: 'mesa 1' } } as unknown as Event;
      component.handleInputTable(event);

      expect(component.resultsTable.length).toBe(1);
      expect(component.resultsTable[0].mesa).toBe('Mesa 1');
    });
    });
});
});