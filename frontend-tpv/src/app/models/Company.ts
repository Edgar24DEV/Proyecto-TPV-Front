export class Company {
  private _id?: number;
  private _nombre?: string;
  private _direccionFiscal?: string;
  private _cif?: string;
  private _razonSocial?: string;
  private _telefono?: string;
  private _correo?: string;
  private _contrasenya?: string;

  constructor(data: {
    id?: number;
    nombre?: string;
    direccionFiscal?: string;
    cif?: string;
    razonSocial?: string;
    telefono?: string;
    correo?: string;
    contrasenya?: string;
  }) {
    this.id = data.id;
    this.nombre = data.nombre!;
    this.direccionFiscal = data.direccionFiscal;
    this.cif = data.cif!;
    this.razonSocial = data.razonSocial;
    this.telefono = data.telefono;
    this.correo = data.correo;
    this.contrasenya = data.contrasenya;
  }

  // Getters
  get id(): number | undefined {
    return this._id;
  }

  get nombre(): string | undefined {
    return this._nombre;
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

  get telefono(): string | undefined {
    return this._telefono;
  }

  get correo(): string | undefined {
    return this._correo;
  }

  get contrasenya(): string | undefined {
    return this._contrasenya;
  }

  // Setters con validación básica
  set id(value: number | undefined) {
    if (value === undefined || value > 0) this._id = value;
    else throw new Error('ID inválido');
  }

  set nombre(value: string) {
    if (value.trim().length > 0) this._nombre = value;
    else throw new Error('Nombre inválido');
  }

  set direccionFiscal(value: string | undefined) {
    this._direccionFiscal = value?.trim();
  }

  set cif(value: string) {
    this._cif = value;
  }

  set razonSocial(value: string | undefined) {
    if (value && value.trim().length > 0) this._razonSocial = value;
    else throw new Error('Razón social inválida');
  }

  set telefono(value: string | undefined) {
    this._telefono = value?.trim();
  }

  set correo(value: string | undefined) {
    if (value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
      throw new Error('Correo inválido');
    }
    this._correo = value;
  }

  set contrasenya(value: string | undefined) {

      this._contrasenya = value;
  }
  
}
