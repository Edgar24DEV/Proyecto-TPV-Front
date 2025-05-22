import { Role } from "./Role";

export class EmployeeRol extends Role {
  private _idEmpleado?: number;
  private _nombre?: string;

  constructor(data: {
    idEmpleado?: number;
    nombre?: string;

    // Props heredadas desde Role
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
    // Llama al constructor de Role
    super({
      id: data.id,
      rol: data.rol,
      productos: data.productos,
      categorias: data.categorias,
      tpv: data.tpv,
      usuarios: data.usuarios,
      mesas: data.mesas,
      restaurante: data.restaurante,
      clientes: data.clientes,
      empresa: data.empresa,
      pago: data.pago,
      idEmpresa: data.idEmpresa,
    });

    // Atributos específicos de EmployeeRol
    this._idEmpleado = data.idEmpleado;
    this._nombre = data.nombre;
  }

  // Getters
  get idEmpleado(): number | undefined {
    return this._idEmpleado;
  }

  get nombre(): string | undefined {
    return this._nombre;
  }


  // Setters con validación básica
  set idEmpleado(value: number | undefined) {
    if (value && value > 0) this._idEmpleado = value;
    else throw new Error("ID inválido");
  }

  set nombre(value: string | undefined) {
    if (value && value.trim().length > 0) this._nombre = value;
    else throw new Error("Nombre inválido");
  }

  static fromJSON(json: any): EmployeeRol {
    return new EmployeeRol({
      idEmpleado: json._idEmpleado ?? json.idEmpleado ?? json.employee_id ?? json.id,
      nombre: json._nombre ?? json.nombre ?? json.employee_name ?? json.name,
      id: json._id ?? json.id,
      rol: json._rol ?? json.rol,
      productos: json._productos ?? json.productos,
      categorias: json._categorias ?? json.categorias,
      tpv: json._tpv ?? json.tpv,
      usuarios: json._usuarios ?? json.usuarios,
      mesas: json._mesas ?? json.mesas,
      restaurante: json._restaurante ?? json.restaurante,
      clientes: json._clientes ?? json.clientes,
      empresa: json._empresa ?? json.empresa,
      pago: json._pago ?? json.pago,
      idEmpresa: json._idEmpresa ?? json.idEmpresa,
    });
  }
  

}
