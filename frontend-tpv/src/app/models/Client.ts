export class Client {
    private _id?: number;
    private _razonSocial?: string;
    private _cif?: string;
    private _direccion?: string;
    private _email?: string;
  
    constructor(data: {
      id?: number;
      razonSocial: string;
      cif: string;
      direccion?: string;
      email?: string;
    }) {
      this.id = data.id;
      this.razonSocial = data.razonSocial;
      this.cif = data.cif;
      this.direccion = data.direccion;
      this.email = data.email;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get razonSocial(): string | undefined {
      return this._razonSocial;
    }
  
    get cif(): string | undefined {
      return this._cif;
    }
  
    get direccion(): string | undefined {
      return this._direccion;
    }
  
    get email(): string | undefined {
      return this._email;
    }
  
    // Setters con validación básica
    set id(value: number | undefined) {
      if (value === undefined || value > 0) this._id = value;
      else throw new Error('ID inválido');
    }
  
    set razonSocial(value: string) {
      if (value.trim().length > 0) this._razonSocial = value;
      else throw new Error('Razón social inválida');
    }
  
    set cif(value: string) {
      this._cif = value;
    }
  
    set direccion(value: string | undefined) {
      this._direccion = value?.trim();
    }
  
    set email(value: string | undefined) {
      if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        throw new Error('Email inválido');
      }
      this._email = value;
    }
  }
  