<template>
  <!--<div class="animated fadeIn">
    <div class="col-lg-12">
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/country/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <b-row>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Filter" class="mb-0">
              <b-input-group>
                <b-form-input v-model="filter" placeholder="Type to Search" />
                <b-btn :disabled="!filter" @click="filter = ''">Clear</b-btn>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-1">
            <b-form-group horizontal label="Sort" class="mb-0">
              <b-input-group>
                <b-form-select v-model="sortBy" :options="sortOptions">
                  <option slot="first" :value="null">-- none --</option>
                </b-form-select>
                <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
                  <option :value="false">Asc</option>
                  <option :value="true">Desc</option>
                </b-form-select>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-2">
            <b-form-group horizontal label="Sort direction" class="mb-0">
              <b-input-group>
                <b-form-select v-model="sortDirection" slot="_append">
                  <option value="asc">Asc</option>
                  <option value="desc">Desc</option>
                  <option value="last">Last</option>
                </b-form-select>
              </b-input-group>
            </b-form-group>
          </b-col>
          <b-col md="6" class="my-2">
            <b-form-group horizontal label="Per page" class="mb-0">
              <b-form-select :options="pageOptions" v-model="perPage" />
            </b-form-group>
          </b-col>
        </b-row>

        <b-table show-empty
                stacked="md"
                :datatable="datatable"
                :items="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                @filtered="onFiltered">
          <template slot="image" slot-scope="row">{{row.value.image}}</template>
          <template slot="name" slot-scope="row">{{row.value.name}}</template>
          <template slot="iso_code_2" slot-scope="row">{{row.value.iso_code_2}}</template>
          <template slot="iso_code_3" slot-scope="row">{{row.value.iso_code_3}}</template>
          <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template>
          <template slot="actions" slot-scope="row">
            <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
              Info modal
            </b-button>
            <b-button size="sm" @click.stop="row.toggleDetails">
              {{ row.detailsShowing ? 'Hide' : 'Show' }} Details
            </b-button>
          </template>
          <template slot="row-details" slot-scope="row">
            <b-card>
              <ul>
                <li v-for="(value, key) in row.item" :key="key">{{ key }}: {{ value}}</li>
              </ul>
            </b-card>
          </template>
        </b-table>

        <b-row class="pull-right">
          <b-col md="6" class="my-2">
            <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
          </b-col>
        </b-row>

        <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
          <pre>{{ modalInfo.content }}</pre>
        </b-modal>
      </b-card>
    </div>
  </div>-->
  <div class="animated fadeIn">
    <div class="col-lg-12">
      <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
      <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
      <b-card header="<i class='fa fa-align-justify'></i> List Data <a href='/#/country/form' class='pull-right btn btn-primary btn-sm'><i class='icon-plus'></i> Add</a>">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Code</th>
              <th>Name</th>
              <th>ISO Code 2</th>
              <th>ISO Code 3</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="data, key in datatable['data']">
              <td>{{++key}}</td>
              <td><img width="30px" :src='data.image'/></td>
              <td>{{data.country_code}}</td>
              <td>{{data.name}}</td>
              <td>{{data.iso_code_2}}</td>
              <td>{{data.iso_code_3}}</td>
              <td>
                <button type="button" @click="viewEdit(data.country_id)" class="btn btn-primary"><i class="icon-note"></i> Edit</button>
                <button type="button" @click="initDelete(data.country_id)" class="btn btn-danger"><i class="icon-trash"></i> Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </b-card>
    </div>
  </div>

</template>

<script>
  const items = [
  { isActive: true, age: 40, name: { first: 'Dickerson', last: 'Macdonald' } },
  { isActive: false, age: 21, name: { first: 'Larsen', last: 'Shaw' } },
  {
    isActive: false,
    age: 9,
    name: { first: 'Mini', last: 'Navarro' },
    _rowVariant: 'success'
  },
  { isActive: false, age: 89, name: { first: 'Geneva', last: 'Wilson' } },
  { isActive: true, age: 38, name: { first: 'Jami', last: 'Carney' } },
  { isActive: false, age: 27, name: { first: 'Essie', last: 'Dunlap' } },
  { isActive: true, age: 40, name: { first: 'Thor', last: 'Macdonald' } },
  {
    isActive: true,
    age: 87,
    name: { first: 'Larsen', last: 'Shaw' },
    _cellVariants: { age: 'danger', isActive: 'warning' }
  },
  { isActive: false, age: 26, name: { first: 'Mitzi', last: 'Navarro' } },
  { isActive: false, age: 22, name: { first: 'Genevieve', last: 'Wilson' } },
  { isActive: true, age: 38, name: { first: 'John', last: 'Carney' } },
  { isActive: false, age: 29, name: { first: 'Dick', last: 'Dunlap' } }
]

	import {fetchList,deleteData} from '../../../../../api/setupmaster/country'
  import Flash from '../../../../../services/flash'
	import axios from 'axios'
	export default{
		data(){
			return{
        backUrl:'/country',
        datatable:[],
        flash: Flash.state,
        items: [],
        fields: [
          { key: 'image', label: 'Image', sortable: true, sortDirection: 'desc' },
          { key: 'code', label: 'Code', sortable: true, sortDirection: 'desc' },
          { key: 'name', label: 'Name', sortable: true, sortDirection: 'desc' },
          { key: 'iso_code_2', label: 'ISO Code 2', sortable: true, sortDirection: 'desc' },
          { key: 'iso_code_3', label: 'ISO Code 3', sortable: true, sortDirection: 'desc' },
          // { key: 'name', label: 'Person Full name', sortable: true, sortDirection: 'desc' },
          // { key: 'age', label: 'Person age', sortable: true, 'class': 'text-center' },
          // { key: 'isActive', label: 'is Active' },
          { key: 'actions', label: 'Actions' }
        ],
        currentPage: 1,
        perPage: 5,
        totalRows: items.length,
        pageOptions: [ 5, 10, 15 ],
        sortBy: null,
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        modalInfo: { title: '', content: '' }
      }
    },
    computed: {
      sortOptions () {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => { return { text: f.label, value: f.key } })
      }
    },
		created(){
      Flash.setLoading(true)
			this.getData();
		},
    methods:{
      info (item, index, button) {
        this.modalInfo.title = `Row index: ${index}`
        this.modalInfo.content = JSON.stringify(item, null, 2)
        this.$root.$emit('bv::show::modal', 'modalInfo', button)
      },
      resetModal () {
        this.modalInfo.title = ''
        this.modalInfo.content = ''
      },
      onFiltered (filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      getData() {
        fetchList().then(response => {
          this.datatable = response
        })
      },
      viewEdit(id){
        this.$router.push(this.backUrl+'/'+id)
      },
      initDelete(id){
        var r = confirm("Are you sure to delete?");
        if (r == true) {
          deleteData(id).then(response => {
            if(response.success == true){
              Flash.setSuccess(response.message)
              this.getData();
            }else{
              Flash.setError(response.message)
              Flash.setLoading(true)
            }
          })
        }
      }
    }
	}
</script>