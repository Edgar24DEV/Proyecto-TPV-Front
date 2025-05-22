export class Product {
    private _id?: number;
    private _nombre?: string;
    private _precio?: number;
    private _imagen?: string;
    private _activo?: boolean;
    private _iva?: number;
    private _idCategoria?: number;
    private _idEmpresa?: number;
  
    constructor(data: {
      id?: number;
      nombre?: string;
      precio?: number;
      imagen?: string;
      activo?: boolean;
      iva?: number;
      idCategoria?: number;
      idEmpresa?: number;
    }) {
      this._id = data.id;
      this._nombre = data.nombre;
      this._precio = data.precio;
      this._imagen = data.imagen;
      this._activo = data.activo;
      this._iva = data.iva;
      this._idCategoria = data.idCategoria;
      this._idEmpresa = data.idEmpresa;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get nombre(): string | undefined {
      return this._nombre;
    }
  
    get precio(): number | undefined {
      return this._precio;
    }
  
    get imagen(): string | undefined {
      return this._imagen;
    }
  
    get activo(): boolean | undefined {
      return this._activo;
    }
  
    get iva(): number | undefined {
      return this._iva;
    }
  
    get idCategoria(): number | undefined {
      return this._idCategoria;
    }
  
    get idEmpresa(): number | undefined {
      return this._idEmpresa;
    }
  
    // Setters con validación básica
    set id(value: number | undefined) {
      if (value && value > 0) this._id = value;
      else throw new Error('ID inválido');
    }
  
    set nombre(value: string | undefined) {
      if (value && value.trim().length > 0) this._nombre = value;
      else throw new Error('Nombre inválido');
    }
  
    set precio(value: number | undefined) {
      if (value && value >= 0) this._precio = value;
      else throw new Error('Precio inválido');
    }
  
    set imagen(value: string | undefined) {
      if (value && value.trim().length > 0) this._imagen = value;
      else throw new Error('Imagen inválida');
    }
  
    set activo(value: boolean | undefined) {
      if (typeof value === 'boolean') this._activo = value;
      else throw new Error('Valor de activo inválido');
    }
  
    set iva(value: number | undefined) {
      if (value !== undefined && value >= 0) this._iva = value;
      else throw new Error('IVA inválido');
    }
  
    set idCategoria(value: number | undefined) {
      if (value && value > 0) this._idCategoria = value;
      else throw new Error('ID de categoría inválido');
    }
  
    set idEmpresa(value: number | undefined) {
      if (value && value > 0) this._idEmpresa = value;
      else throw new Error('ID de empresa inválido');
    }
  }
  