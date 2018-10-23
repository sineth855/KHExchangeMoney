<template>
    <div>
        <b-form hide-footer>
            <b-modal centered :title="'Order Product List'" ok-only size="lg" class="modal-primary" v-model="showOrderProduct" @ok="showOrderProduct = false">
                <b-alert v-if="flash.success" variant="success" show>{{ flash.success }}</b-alert>
                <b-alert v-if="flash.error" variant="danger" show>{{ flash.error }}</b-alert>
                <div>
                    <b-card header="<i class='fa fa-align-justify'></i> Order Product">
                        <!--<div class="pull-right"><a href="javascript:void(0)" @click="viewLoanPaymentForm(1,'','')"><i class="icon-plus"></i> New Payment</a></div>-->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Model</th>
                                    <th>Price</th>
                                    <th>Tax</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data, key in datatable">
                                    <td>{{++key}}</td>
                                    <td>{{data.name}}</td>
                                    <td>{{data.quantity}}</td>
                                    <td>{{data.model}}</td>
                                    <td>{{data.price}}</td>
                                    <td>{{data.tax}}</td>
                                    <td>{{data.total}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <!--<ul class="pagination">
                            <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                            <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>-->
                    </b-card>
                </div>                    
            </b-modal>
        </b-form>
    </div>
</template>

<script>
    import Flash from '../../../../services/flash'

    export default{
        props:[
            
        ],
        data(){
			return{
                datatable:[],
                showForm:false,
                showAccountCredit:false,
                showOrderProduct:false,
                flash:Flash.state,
                account_loan_id:0,
                IsStatusApproved:true
            }
        },
        created(){
            this.showAction = this.showAction
        },
        methods:{
            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                el.classList.add(className);
                } else {
                el.classList.remove(className);
                }
            },
            emitEvent: function() {
                this.$emit('emitEvent')
            },
            eventChild: function() {
                this.showOrderProduct = true
                this.$emit('event_child')
            },
            child_method(data){
                this.datatable = data['data_order_product']
                this.showOrderProduct = true
            }
        }  
    }
</script>