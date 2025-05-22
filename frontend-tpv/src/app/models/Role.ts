export class Role {
    private _id?: number;
    private _rol: string;
    private _productos: boolean;
    private _categorias: boolean;
    private _tpv: boolean;
    private _usuarios: boolean;
    private _mesas: boolean;
    private _restaurante: boolean;
    private _clientes: boolean;
    private _empresa: boolean;
    private _pago: boolean;
    private _idEmpresa?: number;
  
    constructor(data: {
      id?: number;
      rol: string;
      productos: boolean;
      categorias: boolean;
      tpv: boolean;
      usuarios: boolean;
      mesas: boolean;
      restaurante: boolean;
      clientes: boolean;
      empresa: boolean;
      pago: boolean;
      idEmpresa?: number;
    }) {
      this._id = data.id;
      this._rol = data.rol;
      this._productos = data.productos;
      this._categorias = data.categorias;
      this._tpv = data.tpv;
      this._usuarios = data.usuarios;
      this._mesas = data.mesas;
      this._restaurante = data.restaurante;
      this._clientes = data.clientes;
      this._empresa = data.empresa;
      this._pago = data.pago;
      this._idEmpresa = data.idEmpresa;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get rol(): string {
      return this._rol;
    }
  
    get productos(): boolean {
      return this._productos;
    }
  
    get categorias(): boolean {
      return this._categorias;
    }
  
    get tpv(): boolean {
      return this._tpv;
    }
  
    get usuarios(): boolean {
      return this._usuarios;
    }
  
    get mesas(): boolean {
      return this._mesas;
    }
  
    get restaurante(): boolean {
      return this._restaurante;
    }
  
    get clientes(): boolean {
      return this._clientes;
    }
  
    get empresa(): boolean {
      return this._empresa;
    }
  
    get pago(): boolean {
      return this._pago;
    }
  
    get idEmpresa(): number | undefined {
      return this._idEmpresa;
    }
  
    // Setters con validaciones básicas
    set id(id: number | undefined) {
      if (id && id > 0) this._id = id;
      else throw new Error('ID inválido');
    }
  
    set rol(rol: string) {
      if (rol && rol.length > 0) this._rol = rol;
      else throw new Error('Rol inválido');
    }
  
    set productos(value: boolean) {
      this._productos = value;
    }
  
    set categorias(value: boolean) {
      this._categorias = value;
    }
  
    set tpv(value: boolean) {
      this._tpv = value;
    }
  
    set usuarios(value: boolean) {
      this._usuarios = value;
    }
  
    set mesas(value: boolean) {
      this._mesas = value;
    }
  
    set restaurante(value: boolean) {
      this._restaurante = value;
    }
  
    set clientes(value: boolean) {
      this._clientes = value;
    }
  
    set empresa(value: boolean) {
      this._empresa = value;
    }
  
    set pago(value: boolean) {
      this._pago = value;
    }
  
    set idEmpresa(idEmpresa: number | undefined) {
      if (idEmpresa === undefined || idEmpresa > 0) this._idEmpresa = idEmpresa;
      else throw new Error('ID de empresa inválido');
    }
  }
  