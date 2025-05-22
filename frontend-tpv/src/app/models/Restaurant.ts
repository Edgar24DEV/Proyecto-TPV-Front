export class Restaurant {
    private _id?: number;
    private _nombre?: string;
    private _contrasenya?: string;
    private _direccion?: string;
    private _telefono?: string;
    private _direccionFiscal?: string;
    private _cif?: string;
    private _razonSocial?: string;
    private _idEmpresa?: number;
  
    constructor(data: {
      id?: number,
      nombre?: string,
      contrasenya?: string,
      direccion?: string,
      telefono?: string,
      direccionFiscal?: string,
      cif?: string,
      razonSocial?: string,
      idEmpresa?: number
    }) {
      this._id = data.id;
      this._nombre = data.nombre;
      this._contrasenya = data.contrasenya;
      this._direccion = data.direccion;
      this._telefono = data.telefono;
      this._direccionFiscal = data.direccionFiscal;
      this._cif = data.cif;
      this._razonSocial = data.razonSocial;
      this._idEmpresa = data.idEmpresa;
    }
  
    // Getters
    get id(): number | undefined {
      return this._id;
    }
  
    get nombre(): string | undefined {
      return this._nombre;
    }
  
    get contrasenya(): string | undefined {
      return this._contrasenya;
    }
  
    get direccion(): string | undefined {
      return this._direccion;
    }
  
    get telefono(): string | undefined {
      return this._telefono;
    }
  
    get direccionFiscal(): string | undefined {
      return this._direccionFiscal;
    }
  
    get cif(): string | undefined {
      return this._cif;
    }
  
    get razonSocial(): string | undefined {
      return this._razonSocial;
    }
  
    get idEmpresa(): number | undefined {
      return this._idEmpresa;
    }
  
    // Setters
    set id(id: number | undefined) {
      if (id && id > 0) this._id = id;
      else throw new Error('ID inválido');
    }
  
    set nombre(nombre: string | undefined) {
      if (nombre && nombre.length > 0) this._nombre = nombre;
      else throw new Error('Nombre inválido');
    }
  
    set contrasenya(contrasenya: string | undefined) {
      if (contrasenya && contrasenya.length >= 6) this._contrasenya = contrasenya;
      else throw new Error('La contraseña debe tener al menos 6 caracteres');
    }
  
    set direccion(direccion: string | undefined) {
      if (direccion) this._direccion = direccion;
      else throw new Error('Dirección inválida');
    }
  
    set telefono(telefono: string | undefined) {
      if (telefono) this._telefono = telefono;
      else throw new Error('Teléfono inválido');
    }
  
    set direccionFiscal(direccionFiscal: string | undefined) {
      if (direccionFiscal) this._direccionFiscal = direccionFiscal;
      else throw new Error('Dirección fiscal inválida');
    }
  
    set cif(cif: string | undefined) {
      if (cif) this._cif = cif;
      else throw new Error('CIF inválido');
    }
  
    set razonSocial(razonSocial: string | undefined) {
      if (razonSocial) this._razonSocial = razonSocial;
      else throw new Error('Razón social inválida');
    }
  
    set idEmpresa(idEmpresa: number | undefined) {
      if (idEmpresa && idEmpresa > 0) this._idEmpresa = idEmpresa;
      else throw new Error('ID de empresa inválido');
    }
  }
  