export class Employee {
    [x: string]: any;
    private _id?: number;
    private _nombre?: string;
    private _pin?: number;
    private _idRol?: number;
    private _idEmpresa?: number;
  
    constructor(data: {
      id?: number;
      nombre?: string;
      pin?: number;
      idRol?: number;
      idEmpresa?: number;
    }) {
      this._id = data.id;
      this._nombre = data.nombre;
      this._pin = data.pin;
      this._idRol = data.idRol;
      this._idEmpresa = data.idEmpresa;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get nombre(): string | undefined {
      return this._nombre;
    }
  
    get pin(): number | undefined {
      return this._pin;
    }
  
    get idRol(): number | undefined {
      return this._idRol;
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
  
    set pin(value: number | undefined) {
      if (value && value >= 1000 && value <= 9999) this._pin = value;
      else throw new Error('PIN inválido (debe ser de 4 dígitos)');
    }
  
    set idRol(value: number | undefined) {
      if (value && value > 0) this._idRol = value;
      else throw new Error('ID de rol inválido');
    }
  
    set idEmpresa(value: number | undefined) {
      if (value && value > 0) this._idEmpresa = value;
      else throw new Error('ID de empresa inválido');
    }
  }
  