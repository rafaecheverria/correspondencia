<template>
  <div>
    <notifications></notifications>
    <fade-render-transition :duration="100">

    <card>
      <div slot="header">
        <span class="card-title font-weight-normal font-weight-light text-white small text-white">LISTA DE DEPARTAMENTOS</span>
      </div>
      <br>
      <!-- FILTROS DE BÚSQUEDA -->
      <div class="row">
        <div class="col-md-3">
          <el-select class="select-primary"
                      size="large"
                      placeholder="Buscar por:"
                      v-model="criterio">
            <el-option v-for="option in selects.criterios"
                        class="select-primary"
                        :value="option.value"
                        :label="option.label"
                        :key="option.label">
            </el-option>
          </el-select>
        </div>
          <div class="col-md-3 padd">
            <fg-input placeholder="Buscar" v-model="buscar" @keyup.enter="listarDepartamentos(1, buscar, criterio)"></fg-input>
          </div>
          <div class="col-md-3">
            <l-button type="info" outline wide @click="listarDepartamentos(1, buscar, criterio)">
                <span class="btn-label">
                    <i class="fa fa-search"></i>
                </span>
                Buscar
              </l-button>
          </div>

          <div class="col-md-3">
            <l-button type="success" outline wide  @click="openModal('departamento', 'registrar')">
                <span class="btn-label">
                    <i class="fa fa-plus"></i>
                </span>
                Agregar
              </l-button>
          </div>
      </div>

      <!-- LISTA PRINCIPAL DE LAS EMPRESAS -->
      <div class="row">

        <div class="col-sm-12">
          <div class="table-responsive">
            <el-table stripe style="width: 100%;" :data="tableData">
              <el-table-column prop="departamento" minWidth= 300 label="Nombre"></el-table-column>
              <el-table-column prop="abreviado" minWidth= 220 label="Abreviado"></el-table-column>
              <el-table-column prop="sigla" minWidth= 220 label="Siglas"></el-table-column>
                <el-table-column :min-width="120" fixed="right" label="Actions">
                  <template slot-scope="props">
                      <a v-tooltip.top-center="'Agregar Sucursal'" class="btn-success btn-simple btn-link"
                      @click="openModal('departamento', 'sucursal', props.row)"><i class="fa fa-building"></i></a>
                      <a v-tooltip.top-center="'Agregar Cargo'" class="btn-primary btn-simple btn-link"
                      @click="openModal('departamento', 'cargo', props.row)"><i class="fa fa-user-plus"></i></a>
                      <a v-tooltip.top-center="'Editar'" class="btn-warning btn-simple btn-link"
                      @click="openModal('departamento', 'actualizar', props.row)"><i
                      class="fa fa-edit"></i></a>
                    <a v-tooltip.top-center="'Eliminar'" class="btn-danger btn-simple btn-link"><i class="fa fa-times"></i></a>
                  </template>
                </el-table-column>
            </el-table>
          </div>

          <!-- PAGINACION -->

          <div class="card-body">
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">«</a>
              </li>
              <li class="page-item disabled" v-else>
                  <a class="page-link" disabled="disabled">«</a>
              </li>
              <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
              </li>
              <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                  <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">»</a>
              </li>
              <li class="page-item disabled" v-else>
                  <a class="page-link" disabled="disabled">»</a>
              </li>
            </ul>
          </div>

        </div>

      </div>

    </card>
    
    </fade-render-transition>

    <!-- DIALOG -->

    <div class="col-md-12">
      <el-dialog :visible.sync="modal">
        <span slot="title" class="dialog-title" v-text="tituloModal"></span>
        <form>
          <div v-if="tipoAccion==1 || tipoAccion ==2">
          <div class="row">
            <div class="col-md-12">
            <fg-input type="text"
                      name="departamento"
                      label="Departamento"
                      v-validate="modelValidations.departamento"
                      :error="getError('departamento')"
                      :readonly="readonly == 1 ? true : false"
                      v-model="departamento">
            </fg-input>
            
            </div>
           
          </div>
          <br>
          <div class="row">
             <div class="col-md-6">
            <fg-input type="text"
                      name="abreviado"
                      label="Abreviado"
                      v-validate="modelValidations.abreviado"
                      :error="getError('abreviado')"
                      v-model="abreviado">
            </fg-input>
            </div>
            <div class="col-md-6">
            <fg-input type="text"
                      name="sigla"
                      label="Siglas"
                      v-validate="modelValidations.sigla"
                      :error="getError('sigla')"
                      v-model="sigla">
            </fg-input>
            </div> 
          </div>
        </div>
        <br>

        <!-- AGREGAR UNA SUCURSAL A LA EMPRESA SELECCIONADA -->

        <div v-if="tipoAccion == 3">
          <div class="col-md-12">
            <el-select class="select-primary"
                        size="large"
                        placeholder="Región"
                        v-model="region"
                        v-on:change="changeRegion($event)">
              <el-option v-for="region in arrayRegiones"
                          class="select-primary"
                          :value="region.id"
                          :label="region.nombre"
                          :key="region.id">
              </el-option>
            </el-select>
          <span class="text-danger size" v-if="errores.region" v-text="errores.region[0]"></span>
          </div>
          <br>
          <div class="col-md-12">
            <el-select class="select-primary"
                        size="large"
                        placeholder="Comuna"
                        v-model="comuna">
              <el-option v-for="comuna in arrayComunas"
                          class="select-primary"
                          :value="comuna.id"
                          :label="comuna.nombre"
                          :key="comuna.id">
              </el-option>
            </el-select>
            <span class="text-danger size" v-if="errores.comuna_id" v-text="errores.comuna_id[0]"></span>
          </div>
          <br>
          <div class="col-md-12">
            <fg-input type="text"
                      name="direccion"
                      label="Dirección"
                      v-validate="modelValidations.direccion"
                      :error="getError('direccion')"
                      placeholder="Ej: Vicuña Mackena 1208"
                      v-model="direccion">
            </fg-input>
          </div>
        </div>

        <!-- AGREGAR CARGOS A LA EMPRESA -->
        <div v-if="tipoAccion == 4">
          <div class="col-md-12">
          <label>Cargos de la Empresa</label>
          <el-select class="select-primary"
                      size="large"
                      placeholder="Seleccionar"
                      v-model="cargos"
                      multiple>
            <el-option v-for="cargos in arrayCargos"
                        class="select-primary"
                        :value="cargos.id"
                        :label="cargos.nombre"
                        :key="cargos.id">
            </el-option>
          </el-select>
          <span class="text-danger size" v-if="errores.region" v-text="errores.region[0]"></span>
          </div>
        </div>

        </form>
        <span slot="footer" class="dialog-footer">
          <button class="btn btn-danger" @click="closeModal()">Cancel</button>&nbsp;
          <button class="btn btn-success" v-if="tipoAccion == 1" @click="registrarDepartamento()">Guardar</button>
          <button class="btn btn-primary" v-if="tipoAccion == 2" @click="actualizarDepartamento()">Actualizar</button>
          <button class="btn btn-info" v-if="tipoAccion == 3" @click="AgregarSucursal()">Agregar Sucursal</button>
          <button class="btn btn-info" v-if="tipoAccion == 4" @click="AgregarCargos()">Agregar Cargos</button>
        </span>
      </el-dialog>
    </div>

  </div>
</template>
<script>
  import {Dialog, Table, TableColumn, Tag, Select, Option} from 'element-ui'
  import LSwitch from '../components/Switch.vue'
  import LPagination from '../components/Pagination.vue'
  import {FadeRenderTransition } from '@/components/index'

  export default{
    components: {
      LSwitch,
      [Dialog.name]: Dialog,
      [Tag.name]: Tag,
      [Table.name]: Table,
      [TableColumn.name]: TableColumn,
      LPagination,
      [Select.name]: Select,
      [Option.name]: Option,
      FadeRenderTransition
    },
    data () {
      return {
        modal        : false,
        readonly     : 0,
        departamento : '',
        sigla        : '',
        abreviado    : '',
        tituloModal  : '',
        tableData    : [],
        tipoAccion   : 0,
        departamento_id   : 0,
        pagination   : {
          'total'        : 0,
          'current_page' : 0,
          'per_page'     : 0,
          'last_page'    : 0,
          'from'         : 0,
          'to'           : 0,
        },
        offset   : 4,
        criterio : 'departamento',
        buscar   : '',
        selects: {
          simple : '',
          criterios: [
            {value: 'departamento', label: 'Departamento'},
            {value: 'sigla', label: 'Siglas'}
          ],
        },
        errores      : [],
        arrayRegiones: [],
        arrayComunas : [],
        arrayCargos  : [],
        cargos       : [],
        region_id    : 0,
        modelValidations: {
          departamento: {
            required: true,
          },
          abreviado: {
            required: true,
            min: 7
          },
          sigla: {
            required: true,
            min: 2
          },
        }
      }
    },
    computed: {
      isActived: function(){
        return this.pagination.current_page;
      },
      //Calcula los elementos de la paginación
      pagesNumber: function() {
          if(!this.pagination.to) {
              return [];
          }
          
          var from = this.pagination.current_page - this.offset; 
          if(from < 1) {
              from = 1;
          }

          var to = from + (this.offset * 2); 
          if(to >= this.pagination.last_page){
              to = this.pagination.last_page;
          }  

          var pagesArray = [];
          while(from <= to) {
              pagesArray.push(from);
              from++;
          }
          return pagesArray;             
      }

    },
    methods: {
      format_fecha: function(d){
          return moment(d).format("dddd, D MMMM YYYY, h:mm:ss a");
      },
      listarDepartamentos(page, buscar, criterio){
        let me=this;
        var url = 'departamentos?page='+ page + '&buscar='+ buscar + '&criterio='+ criterio;
        axios.get(url).then(function (response) {
          var respuesta = response.data;
          me.tableData = respuesta.departamentos.data;
          me.pagination = respuesta.pagination;
          }).catch(function (error) {
          console.log(error);
        })
      },
      cambiarPagina(page, buscar, criterio){
        let me = this;
        me.pagination.current_page = page;
        me.listarDepartamentos(page, buscar, criterio);
      },
      openModal (modelo, accion, data = []) {
        switch(modelo){
          case 'departamento': 
            switch(accion){
              case 'registrar':
                this.clearError()
                this.errores      = [],
                this.modal        = true;
                this.departamento       = '';
                this.abreviado        = '';
                this.sigla    = '';
                this.tituloModal  = 'REGISTRAR DEPARTAMENTO';
                this.tipoAccion   = 1;
                this.readonly     = 0;
              break;
              case 'actualizar': 
                this.clearError()
                this.errores      = [],
                this.modal        = true;
                this.tituloModal  = 'ACTUALIZAR DEPARTAMENTO ' +data['departamento'].toUpperCase();
                this.tipoAccion   = 2;
                this.departamento = data['departamento'];
                this.abreviado    = data['abreviado'];;
                this.sigla        = data['sigla'];;
                this.departamento_id   = data['id'];
              break;
              case 'sucursal':
                let me = this;
                var url = 'regiones/selectRegion/';
                axios.get(url).then(function (response) {
                 var respuesta = response.data;
                 //Obtiene las regiones
                 me.arrayRegiones = respuesta.regiones;
                }).catch(function (error) {
                  console.log(error);
                })
                this.errores      = [],
                this.modal        = true;
                this.tituloModal  = 'AGREGAR SUCURSAL A LA EMPRESA'+data['nombre'].toUpperCase();
                this.tipoAccion   = 3;
                this.empresa_id   = data['id'];
                this.nombre       = data['nombre'];
                this.clearError()
              break;
              case 'cargo':
                var url = 'cargos/selectCargo/'+data["id"];
                axios.get(url).then((response) => {
                 var respuesta = response.data;
                 //Obtiene todos los cargos
                 this.arrayCargos = respuesta.cargos;
                 //Obtiene todos los cargos asociados a la empresa seleccionada
                 this.cargos = respuesta.my_cargos;
                }).catch(function (error) {
                  console.log(error);
                })
                this.errores      = [],
                this.modal        = true;
                this.tituloModal  = 'AGREGAR CARGOS A LA EMPRESA ' +data['nombre'].toUpperCase();
                this.tipoAccion   = 4;
                this.empresa_id   = data['id'];
                this.nombre       = data['nombre'];
                this.clearError()
              break;
            }
        }
      },
      changeRegion(id){
        let me = this;
        me.arrayComunas = [];
        me.comuna = '';
        var url = 'comunas/selectComuna/'+id;
        axios.get(url).then(function (response) {
          var respuesta = response.data;
          //Obtiene las comunas de la regiona seleccionada
          me.arrayComunas = respuesta.comunas;
          }).catch(function (error) {
          console.log(error);
        })
      },
      AgregarSucursal () {
       let me = this;
       axios.post('sucursales/registrar',{
         'comuna_id'     : me.comuna,
         'empresa_id'    : me.empresa_id,
         'direccion'  : me.direccion,
         'region'     : me.region,
       }).then(function(response){
          me.closeModal();
          me.listarDepartamentos(1, '', '')
          me.notificacion('top', 'right', response.data.direccion, 'registrada')
       }).catch(error => {
         if(error.response.status == 422){
           me.errores = error.response.data.errors;
         }
       })
      },
      AgregarCargos () {
       let me = this;
       axios.post('cargos/agregarCargosEmpresa/'+me.empresa_id,{
         'cargos' : me.cargos,
       }).then(function(response){
          me.closeModal();
          me.listarDepartamentos(1, '', '')
          me.notificacion('top', 'right', response.data.direccion, 'registrada')
       }).catch(error => {
         if(error.response.status == 422){
           me.errores = error.response.data.errors;
         }
       })
      },
      closeModal () {
        this.modal = false;
        this.nombre   = '';
        this.email    = '';
        this.direccion= '';
        this.telefono = '';
        this.rut      = '';
        this.razon_social = '';
      },
      registrarDepartamento () {
       this.validate()
       let me = this;
       axios.post('departamentos/registrar',{
         'departamento' : this.departamento,
         'abreviado'    : this.abreviado,
         'sigla'       : this.sigla,
       }).then(function(response){
          me.closeModal();
          me.listarDepartamentos(1, '', '')
          me.notificacion('top', 'right', response.data.departamento, 'registró')
          me.clearError()
       }).catch(error => {
         console.log(error)
       })
      },
      actualizarDepartamento(){
          let me = this;
          axios.put('departamentos/actualizar',{
            'id'           : this.departamento_id,
            'departamento' : this.departamento,
            'abreviado'    : this.abreviado,   
            'sigla'        : this.sigla,
          }).then(function (response) {
              me.closeModal();
              me.listarDepartamentos(1, '', '');
              me.notificacion('top', 'right', response.data.departamento, 'actualizada')
              me.clearError()
          }).catch(error => {
            console.log(error)            
          })
      },
      notificacion (verticalAlign, horizontalAlign, departamento, tipo) {
        this.$notify(
          {
            message: '<span>El departamento <b>'+departamento+'</b> - ha sido '+tipo+' exitosamente!.</span>',
            icon: 'nc-icon nc-app',
            horizontalAlign: horizontalAlign,
            verticalAlign: verticalAlign,
            type: 'success'
          })
      },
      getError (fieldName) {
        return this.errors.first(fieldName)
      },
      clearError(){
        this.$validator.reset();
      },
      validate() {
         return this.$validator.validateAll().then(res => {
            this.$emit('on-validated', res, this.model)
            return res  
            })

    	  // this.$validator.validateAll().then(success => {
        //  if (! success) {
        //  	return;
        //  }
        //  post().then(() => {
        //    this.email = '';
        //    alert('success');
        //  }).then(() => {
        //  	 this.errors.clear();
        //  });
        // });
      }
    },
    mounted() {
      this.listarDepartamentos(1, this.buscar, this.criterio);
      //traductor de mensajes de errores del componente
      this.$validator.localize('es', {
        messages: {
          required: (field) => '* ' + field + ' es requerido',
          min: (field) => '* ' + field + ' debe tener un minimo de 5 caracteres',
          numeric: (field) => '* ' + field + ' debe tener sólo números'
        },
        attributes: {
          departamento : 'Departamento',
          abreviado    : 'Abreviado',
          sigla        : 'Sigla',
        } 
      })
    }
    
  }
</script>
<style>
.padd{
  padding: 0px 0px;
}
.el-select {
  display: block;
}

</style>
