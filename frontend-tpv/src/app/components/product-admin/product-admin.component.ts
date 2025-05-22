import { Component, OnInit } from '@angular/core';
import { HeaderTpvComponent } from '../header-tpv/header-tpv.component';
import { CommonModule } from '@angular/common';
import {
  FormBuilder,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from '@angular/forms';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { EmployeeRol } from 'src/app/models/Employee-rol';
import { Product } from 'src/app/models/Product';
import { ProductService } from 'src/app/services/product.service';
import { Category } from 'src/app/models/Category';
import { CategoryService } from 'src/app/services/category.service';
import { AlertController } from '@ionic/angular';
import { AppComponent } from 'src/app/app.component';
import { Router } from '@angular/router';
import { AlertService } from 'src/app/services/alert.service';

@Component({
  standalone: true,
  selector: 'app-product-admin',
  templateUrl: './product-admin.component.html',
  styleUrls: ['./product-admin.component.scss'],
  imports: [IonicModule, CommonModule, ReactiveFormsModule, FormsModule],
})
export class ProductAdminComponent implements OnInit {
  productForm!: FormGroup;
  productRestForm!: FormGroup;
  employeeRol!: EmployeeRol;
  idCompany!: number;
  idRestaurant!: number;
  companyName!: string;
  isDisabled: boolean = false;
  showProductModal: boolean = false;
  showProductRestModal: boolean = false;
  createProduct: boolean = false;
  productosCompany: Product[] = [];
  productosRestaurant: Product[] = [];
  productosCompanyNotRestaurant: Product[] = [];
  listCategories: Category[] = [];
  listAllCategories: Category[] = [];
  product: Product = new Product({}); // Inicializado correctamente
  selectedImagePreview: string | ArrayBuffer | null = null;
  selectedImageFile: File | null = null;
  defaultImage: string = 'assets/defect-image.png';
  activeProduct!: boolean;
  productRest!: Product;
  formSubmitted: boolean = false;
  selectedProducts: number[] = [];

  constructor(
    private fb: FormBuilder,
    private productService: ProductService,
    private categoryService: CategoryService,
    private alertController: AlertController,
    private appComponent: AppComponent,
    private router: Router,
    private alertService: AlertService,
  ) {}

  ngOnInit() {
    this.initializeData();
    this.initForm();
    this.loadInitialData();
    this.appComponent.reloadHeader();
  }

  ionViewWillEnter() {
    this.initializeData();
    this.initForm();
    this.loadInitialData();
    this.appComponent.reloadHeader();
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
    } else {
      this.idRestaurant = 0;
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
      if(!this.employeeRol.productos){
        this.router.navigate(['employees/panel']);
        return;
      }
      if(!this.idRestaurant){
        this.router.navigate(['']);
        }
    }

    this.appComponent.reloadHeader();
  }

  private initForm() {
    this.productForm = this.fb.group({
      nombre: [
        '',
        [
          Validators.required,
          Validators.minLength(2),
          Validators.maxLength(100),
        ],
      ],
      imagen: [null, [Validators.required]],
      categoria: ['', Validators.required],
      precio: [
        '',
        [Validators.required, Validators.min(0.01), Validators.max(9999.99)],
      ],
      activo: [true, Validators.required],
    });

    this.productRestForm = this.fb.group({
      products: [[]],
    });
  }

  onCheckboxChange(event: any, id: number) {
    const checked = event.detail.checked;
    if (checked) {
      this.selectedProducts.push(id);
    } else {
      const index = this.selectedProducts.indexOf(id);
      if (index >= 0) {
        this.selectedProducts.splice(index, 1);
      }
    }
    this.productRestForm.get('products')?.setValue(this.selectedProducts);
  }

  saveProductRest() {
    this.selectedProducts.map((product) => {
      this.productService
        .postProductRestaurantRelation(product, this.idRestaurant)
        .subscribe({
          next: (data) => {
            this.listOfCategories();
          },
          error: (err) => this.alertService.show('Error', 'Error al asignar el producto al restaurante.', 'error')
        });
    });
    this.showProductRestModal = false;
    this.selectedProducts = [];
    this.listOfProducts();
  }
  private loadInitialData() {
    this.listOfProducts();
    this.listOfCategories();
  }

  cancel() {
    this.showProductModal = false;
    this.showProductRestModal = false;
    this.resetImageSelection();
  }

  uploadImage(): Promise<string | null> {
    return new Promise((resolve, reject) => {
      if (!this.selectedImageFile) {
        resolve(null);
        return;
      }

      const formData = new FormData();
      formData.append('imagen', this.selectedImageFile);

      this.productService.uploadImage(formData).subscribe({
        next: (response) => {
          // Asumiendo que el backend responde con { path: 'products/archivo.jpg' }
          resolve(response.path);
        },
        error: (err) => {
          this.alertService.show('Error', 'Error al subir la imagen.', 'error')
          reject(err);
        },
      });
    });
  }

  onFileChange(event: Event) {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (file) {
      this.selectedImageFile = file;
      const reader = new FileReader();

      reader.onload = () => {
        this.selectedImagePreview = reader.result;
        this.productForm.patchValue({
          imagen: reader.result?.toString().split(',')[1], // solo base64 si nueva
        });
      };

      reader.readAsDataURL(file);
    }
  }

  async deleteProduct() {
    const alert = await this.alertController.create({
      header: 'Confirmación',
      message: '¿Estás seguro de que deseas dar de baja este producto?',
      buttons: [
        {
          text: 'Cancelar',
          role: 'cancel',
          cssClass: 'secondary',
          handler: () => {},
        },
        {
          text: 'Aceptar',
          handler: () => {
            let idProducto = this.product.id;
            this.productService.deleteProduct(idProducto!).subscribe({
              next: (data) => {
                this.listOfProducts();
                this.showProductModal = false;
              },
              error: (err) => {
                this.alertService.show('Error', 'Error al elinbiar el producto.', 'error')
              },
            });
          },
        },
      ],
    });

    await alert.present();
  }

  listOfProducts() {
    this.productService
      .getListProductsCompany(
        !this.idCompany ? this.employeeRol.idEmpresa! : this.idCompany
      )
      .subscribe({
        next: (data) => {
          this.productosCompany = data;
          this.results = [...this.productosCompany];
          if (this.idRestaurant) {
            this.productService
              .getAllProductsRestaurant(this.idRestaurant)
              .subscribe({
                next: (data) => {
                  this.productosRestaurant = data;
                  this.results = [...this.productosRestaurant];
                  this.getAvailableRestaurants();
                },
                error: (err) => {
                  console.error('Error al cargar productos:', err);
                },
              });
          }
        },
        error: (err) => {
          console.error('Error al cargar productos:', err);
        },
      });
  }
  getAvailableRestaurants() {
  const assignedIds = this.productosRestaurant.map((r) => r.id);
  this.productosCompanyNotRestaurant = this.productosCompany.filter(
    (prod) => !assignedIds.includes(prod.id) && prod.activo
  );
}

  openFileInput() {
    const fileInput = document.getElementById('fileInput') as HTMLInputElement;
    fileInput?.click();
  }

  async saveProduct() {
    if (!this.selectedImageFile && this.createProduct) {
      this.productForm.get('imagen')?.setErrors({ required: true });
      this.productForm.get('imagen')?.markAsTouched();
      return;
    }

    if (this.productForm.invalid) {
      this.productForm.markAllAsTouched();
      return;
    }

    try {
      let imagePath: string | null = null;
      this.formSubmitted = true;
      if (this.selectedImageFile) {
        imagePath = await this.uploadImage();
      } else {
        imagePath = this.product.imagen!;
      }

      const newProduct = new Product({
        nombre: this.productForm.value.nombre,
        imagen: imagePath!,
        idCategoria: this.productForm.value.categoria,
        precio: this.productForm.value.precio,
        activo: this.productForm.value.activo,
      });

      this.activeProduct = this.productForm.value.activo;

      if (this.createProduct) {
        this.productService
          .postProduct(
            newProduct,
            !this.idCompany ? this.employeeRol.idEmpresa! : this.idCompany
          )
          .subscribe({
            next: (data) => {
              this.showProductModal = false;
              this.listOfProducts();
              if (!this.idCompany) {
                this.productService
                  .postProductRestaurant(data, this.idRestaurant)
                  .subscribe({
                    next: () => {
                      this.presentAlert('Éxito', 'Relación creada con éxito');
                    },
                    error: (err) => {
                      this.presentAlert(
                        'Error',
                        'Error en la relación producto-restaurante'
                      );
                    },
                  });
              }
            },
            error: (err) => {
              this.presentAlert('Error', 'Error al crear el producto');
            },
          });
      } else {
        newProduct.id = this.product.id;
        if (!this.idCompany) {
          newProduct.activo = this.product.activo;
        }

        this.productService.putProduct(newProduct).subscribe({
          next: (data) => {
            this.presentAlert('Éxito', 'Producto actualizado con éxito');
            this.product = data;
            this.showProductModal = false;
            this.listOfProducts();
          },
          error: (err) => {
            this.presentAlert('Error', 'Error al actualizar el producto');
          },
        });

        if (!this.idCompany) {
          this.productService
            .putProductRestaurant(
              this.product,
              this.activeProduct,
              this.idRestaurant
            )
            .subscribe({
              next: () => {
                this.listOfProducts();
              },
              error: (err) => {
                this.presentAlert('Error', 'Error al actualizar la relación');
              },
            });
        }
      }
    } catch (e) {
      this.presentAlert('Error', 'Error general al guardar producto');
    }
  }

  // Método para mostrar alertas
  async presentAlert(header: string, message: string) {
    const alert = await this.alertController.create({
      header: header,
      message: message,
      buttons: ['Aceptar'],
    });
    await alert.present();
  }

  public results = [...this.productosRestaurant];

  // Añade estas propiedades a tu clase
  selectedCategoryId?: number;
  filteredCategories: Category[] = [];

  // Modifica el método listOfCategories
  listOfCategories() {
    
      this.categoryService.getListCategoryCompany(this.idCompany ? this.idCompany : this.employeeRol.idEmpresa!).subscribe({
        next: (categories) => {
          categories.sort((a, b) =>
            (a.categoria || '').localeCompare(b.categoria || '', 'es', { sensitivity: 'base' })
          );
          this.listCategories = categories;
          this.listAllCategories = categories;
          this.filteredCategories = [...categories];
          
          // Añade opción "Todas las categorías"
          this.filteredCategories.unshift({
            id: undefined,
            categoria: 'Todas las categorías',
            activo: true,
            idEmpresa: this.idCompany,
          } as Category);
        },
        error: (err) => this.alertService.show('Error', 'Error al generar la factura.', 'error'),
      });
   if(this.idRestaurant) {
      this.categoryService
        .getListCategoryRestaurant(this.idRestaurant)
        .subscribe({
          next: (categories) => {
            categories.sort((a, b) =>
              (a.categoria || '').localeCompare(b.categoria || '', 'es', { sensitivity: 'base' })
            );
            this.listCategories = categories;
            this.filteredCategories = [...categories];
            // Añade opción "Todas las categorías"
            this.filteredCategories.unshift({
              id: undefined,
              categoria: 'Todas las categorías',
              activo: true,
              idEmpresa: this.employeeRol.idEmpresa,
            } as Category);
          },
          error: (err) => console.error('Error al cargar categorías:', err),
        });
        
    }
  }

  // Añade este método para filtrar por categoría
  filterByCategory(categoryId?: number) {
    this.selectedCategoryId = categoryId;
    if (isNaN(categoryId!)) {
      if (this.idCompany) {
        this.results = this.productosCompany;
      } else {
        this.results = this.productosRestaurant;
      }

      return;
    }

    if (this.idCompany) {
      this.results = this.productosCompany.filter(
        (product) => product.idCategoria === categoryId
      );
    } else {
      this.results = this.productosRestaurant.filter(
        (product) => product.idCategoria === categoryId
      );
    }
  }

  // Modifica handleInput para que funcione con el filtro de categoría
  handleInput(event: Event) {
    const target = event.target as HTMLIonSearchbarElement;
    const query = target.value?.toLowerCase() || '';
    let filtered;

    if (this.idCompany) {
      filtered = [...this.productosCompany];
    } else {
      filtered = [...this.productosRestaurant];
    }

    // Aplicar filtro de categoría primero
    if (this.selectedCategoryId) {
      filtered = filtered.filter(
        (p) => p.idCategoria === this.selectedCategoryId
      );
    }

    // Aplicar filtro de búsqueda
    this.results = filtered.filter((d) =>
      d.nombre!.toLowerCase().includes(query)
    );
  }

  showCreate() {
    this.product = new Product({});
    this.resetImageSelection();
    this.productForm.reset({
      activo: true,
    });
    this.showProductModal = true;
    this.createProduct = true;
    this.isDisabled = false;
  }

  showAsign() {
    this.product = new Product({});
    this.resetImageSelection();
    this.productForm.reset({
      activo: true,
    });
    this.showProductRestModal = true;
    this.showProductModal = false;
    this.isDisabled = false;
  }

  showEdit(id: number) {
    this.getProduct(id);
    this.createProduct = false;
    this.isDisabled = false;
  }

  showInfo(id: number) {
    this.getProduct(id);
    this.createProduct = false;
    this.isDisabled = true;
  }

  getProductRestaurant(id: number) {
    this.productService.getProductRestaurant(id, this.idRestaurant).subscribe({
      next: (data) => {
        this.activeProduct = data;
      },
      error: (err) => {
        this.presentAlert(
          'Error',
          'Error al cargar el activo del producto con el restaurante'
        );
      },
    });
  }

  getProduct(id: number) {
    if (!this.idCompany) {
      this.getProductRestaurant(id);
    }
    this.productService.getProduct(id).subscribe({
      next: (product) => {
        this.product = product;
        this.selectedImagePreview = product.imagen
          ? `http://localhost:80/storage/${product.imagen}`
          : this.defaultImage;

        this.productForm.patchValue({
          nombre: product.nombre,
          categoria: product.idCategoria,
          precio: product.precio,
          activo: this.idCompany ? product.activo : this.activeProduct,
          imagen: product.imagen,
        });

        this.showProductModal = true;
      },
      error: (err) => {
        this.presentAlert('Error', 'Error al cargar el producto');
      },
    });
  }

  private resetImageSelection() {
    this.selectedImagePreview = null;
    this.selectedImageFile = null;
  }

  getImageSrc(): string {
    if (this.selectedImagePreview) {
      return this.selectedImagePreview.toString();
    }
    return this.product?.imagen
      ? `http://localhost:80/storage/${this.product.imagen}`
      : this.defaultImage;
  }
  isInvalid(controlName: string): boolean {
    const control = this.productForm.get(controlName);
    return !!(
      control &&
      control.invalid &&
      (control.touched || this.formSubmitted)
    );
  }
}
