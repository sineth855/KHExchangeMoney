import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '@/containers/Full'

// Views
import Dashboard from '@/views/Dashboard'
import Charts from '@/views/Charts'
import Widgets from '@/views/Widgets'

// View - Account Master
import AccountMasterIndex from '@/views/components/MerchantAccount/AccountMaster/AccountMasterIndex'
import AccountMasterForm from '@/views/components/MerchantAccount/AccountMaster/AccountMasterForm'
import AccountIndex from '@/views/components/MerchantAccount/AccountMaster/AccountIndex'

import AccountCreditIndex from '@/views/components/MerchantAccount/AccountCredit/AccountCreditIndex'
import AccountCreditForm from '@/views/components/MerchantAccount/AccountCredit/AccountCreditForm'

import AccountLoanIndex from '@/views/components/MerchantAccount/AccountLoan/AccountLoanIndex'
import AccountLoanForm from '@/views/components/MerchantAccount/AccountLoan/AccountLoanForm'

import AccountTransactionIndex from '@/views/components/MerchantAccount/AccountTransaction/AccountTransactionIndex'
import AccountTransactionForm from '@/views/components/MerchantAccount/AccountTransaction/AccountTransactionForm'
// Stock Balance
import BankAccountIndex from '@/views/components/StockBalance/BankAccount/BankAccountIndex'
import BankAccountForm from '@/views/components/StockBalance/BankAccount/BankAccountForm'
// Account Owner
import AccountOwnerIndex from '@/views/components/StockBalance/AccountOwner/AccountOwnerIndex'
import AccountOwnerForm from '@/views/components/StockBalance/AccountOwner/AccountOwnerForm'
import AccountOwnerDetail from '@/views/components/StockBalance/AccountOwner/AccountOwnerDetail'
// View Exchange Rate 
import ExchangeRateIndex from '@/views/components/Currency/ExchangeRate/ExchangeRateIndex'
import ExchangeRateForm from '@/views/components/Currency/ExchangeRate/ExchangeRateForm'
// View Exchange Rate History
import ExchangeRateHistoryIndex from '@/views/components/Currency/ExchangeRateHistory/ExchangeRateHistoryIndex'
import ExchangeRateHistoryForm from '@/views/components/Currency/ExchangeRateHistory/ExchangeRateHistoryForm'

// View Setup - Account Rule
import AccountRuleIndex from '@/views/components/SetupMaster/AccountRule/AccountRuleIndex'
import AccountRuleForm from '@/views/components/SetupMaster/AccountRule/AccountRuleForm'
// View Setup - Stock Status
import StockStatusIndex from '@/views/components/SetupMaster/StockStatus/StockStatusIndex'
import StockStatusForm from '@/views/components/SetupMaster/StockStatus/StockStatusForm'
// View Setup - Currency
import CurrencyIndex from '@/views/components/SetupMaster/Currency/CurrencyIndex'
import CurrencyForm from '@/views/components/SetupMaster/Currency/CurrencyForm'
// View Setup - Term Day
import TermDayIndex from '@/views/components/SetupMaster/TermDay/TermDayIndex'
import TermDayForm from '@/views/components/SetupMaster/TermDay/TermDayForm'
// View Setup - Delivery Method
import DeliveryMethodIndex from '@/views/components/SetupMaster/DeliveryMethod/DeliveryMethodIndex'
import DeliveryMethodForm from '@/views/components/SetupMaster/DeliveryMethod/DeliveryMethodForm'

// View Setup - Restrict Type
import RestrictTypeIndex from '@/views/components/SetupMaster/RestrictType/RestrictTypeIndex'
import RestrictTypeForm from '@/views/components/SetupMaster/RestrictType/RestrictTypeForm'

// View Setup - Rule Type
import RuleTypeIndex from '@/views/components/SetupMaster/RuleType/RuleTypeIndex'
import RuleTypeForm from '@/views/components/SetupMaster/RuleType/RuleTypeForm'

// View Setup - Transaction Charge Fee
import TransactionChargeFeeIndex from '@/views/components/SetupMaster/TransactionChargeFee/TransactionChargeFeeIndex'
import TransactionChargeFeeForm from '@/views/components/SetupMaster/TransactionChargeFee/TransactionChargeFeeForm'

// View Setup - Transaction Rule
import TransactionRuleIndex from '@/views/components/SetupMaster/TransactionRule/TransactionRuleIndex'
import TransactionRuleForm from '@/views/components/SetupMaster/TransactionRule/TransactionRuleForm'

// View Setup - Delivery Method Type
import DeliveryMethodTypeIndex from '@/views/components/SetupMaster/DeliveryMethodType/DeliveryMethodTypeIndex'
import DeliveryMethodTypeForm from '@/views/components/SetupMaster/DeliveryMethodType/DeliveryMethodTypeForm'

// View Setup - Amount Top Up
import AmountTopUpIndex from '@/views/components/SetupMaster/AmountTopUp/AmountTopUpIndex'
import AmountTopUpForm from '@/views/components/SetupMaster/AmountTopUp/AmountTopUpForm'

// View Setup - Interest Rate Period
import InterestRatePeriodIndex from '@/views/components/SetupMaster/InterestRatePeriod/InterestRatePeriodIndex'
import InterestRatePeriodForm from '@/views/components/SetupMaster/InterestRatePeriod/InterestRatePeriodForm'

import InterestMethodIndex from '@/views/components/SetupMaster/InterestMethod/InterestMethodIndex'
import InterestMethodForm from '@/views/components/SetupMaster/InterestMethod/InterestMethodForm'

// View Setup - Country
import CountryIndex from '@/views/components/SetupMaster/Country/CountryIndex'
import CountryForm from '@/views/components/SetupMaster/Country/CountryForm'

// View Setup - Payment Cycle
import PaymentCycleIndex from '@/views/components/SetupMaster/PaymentCycle/PaymentCycleIndex'
import PaymentCycleForm from '@/views/components/SetupMaster/PaymentCycle/PaymentCycleForm'

// View Setup - Account Type
import AccountTypeIndex from '@/views/components/SetupMaster/AccountType/AccountTypeIndex'
import AccountTypeForm from '@/views/components/SetupMaster/AccountType/AccountTypeForm'

// View Setup - Fly Location
import FlyLocationIndex from '@/views/components/SetupMaster/FlyLocation/FlyLocationIndex'
import FlyLocationForm from '@/views/components/SetupMaster/FlyLocation/FlyLocationForm'

// View Setup - Product Loan
import ProductLoanIndex from '@/views/components/SetupMaster/ProductLoan/ProductLoanIndex'
import ProductLoanForm from '@/views/components/SetupMaster/ProductLoan/ProductLoanForm'

// View Booking - Booking Type
import BookingTypeIndex from '@/views/components/Bookings/BookingType/BookingTypeIndex'
import BookingTypeForm from '@/views/components/Bookings/BookingType/BookingTypeForm'

// View Booking - Booking
import BookingIndex from '@/views/components/Bookings/Bookings/BookingIndex'
import BookingForm from '@/views/components/Bookings/Bookings/BookingForm'

// View Vouchers - Vouchers
import VoucherIndex from '@/views/components/Vouchers/Vouchers/VoucherIndex'
import VoucherForm from '@/views/components/Vouchers/Vouchers/VoucherForm'

import BuyVoucherIndex from '@/views/components/Vouchers/Vouchers/BuyVoucherIndex'
import BuyVoucherView from '@/views/components/Vouchers/Vouchers/BuyVoucherView'
import BuyVoucherForm from '@/views/components/Vouchers/Vouchers/BuyVoucherForm'

// View Buy Vouchers - Buy Vouchers
import BuyVoipIndex from '@/views/components/Vouchers/BuyVoip/BuyVoipIndex'
import BuyVoipForm from '@/views/components/Vouchers/BuyVoip/BuyVoipForm'

// View Category
import CategoryIndex from '@/views/components/Catalog/Category/CategoryIndex'
import CategoryForm from '@/views/components/Catalog/Category/CategoryForm'

// View Product
import ProductIndex from '@/views/components/Catalog/Product/ProductIndex'
import ProductForm from '@/views/components/Catalog/Product/ProductForm'

// View Money - Send Money
import SendMoneyIndex from '@/views/components/Money/SendMoney/SendMoneyIndex'
import SendMoneyForm from '@/views/components/Money/SendMoney/SendMoneyForm'

// View Money - Transfer Money
import TransferMoneyIndex from '@/views/components/Money/TransferMoney/TransferMoneyIndex'
import TransferMoneyForm from '@/views/components/Money/TransferMoney/TransferMoneyForm'

// View Setting - Configuration
import ConfigurationIndex from '@/views/components/Setting/ConfigurationIndex'

// View Money - Users
import OrderIndex from '@/views/components/Order/OrderIndex'

// View Money - Users
import UserIndex from '@/views/components/Users/Users/UserIndex'
import UserForm from '@/views/components/Users/Users/UserForm'

// View Money - User Groups
import UserGroupIndex from '@/views/components/Users/UserGroups/UserGroupIndex'
import UserGroupForm from '@/views/components/Users/UserGroups/UserGroupForm'

// Live Chat View
import LiveChat from '@/views/components/LiveChat/LiveChat'

// Views - Components
import Buttons from '@/views/components/Buttons'
import SocialButtons from '@/views/components/SocialButtons'
import Cards from '@/views/components/Cards'
import Forms from '@/views/components/Forms'
import Modals from '@/views/components/Modals'
import Switches from '@/views/components/Switches'
import Tables from '@/views/components/Tables'

// Views - Icons
import FontAwesome from '@/views/icons/FontAwesome'
import SimpleLineIcons from '@/views/icons/SimpleLineIcons'

// Views - Pages
import Page404 from '@/views/pages/Page404'
import Page500 from '@/views/pages/Page500'
import Login from '@/views/pages/Login'
import Register from '@/views/pages/Register'

import { requireAuth } from '../../api/auth/checkAuth';

Vue.use(Router)

export default new Router({
  mode: 'hash', // Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior: () => ({ y: 0 }),
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: Full,
      beforeEnter: requireAuth,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'charts',
          name: 'Charts',
          component: Charts
        },
        // ######## Stock Account ########
        {
          path: 'stocks',
          redirect: '/stocks/bank-account',
          name: 'Back Account',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'bank-account',
              name: 'Back Account List',
              component: BankAccountIndex
            },
            {
              path: 'bank-account/form',
              name: 'Back Account Form',
              component: BankAccountForm
            },
            {
              path:'bank-account/:id',
              name:'Back Account Edit',
              component: BankAccountForm,
              props:true
            }
          ]
        },
        // ######## Exchange Rate ########
        {
          path: 'exchange-rate',
          redirect: '/exchange-rate',
          name: 'Exchange Rate',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Exchange Rate List',
              component: ExchangeRateIndex
            },
            {
              path: 'form',
              name: 'Exchange Rate Form',
              component: ExchangeRateForm
            },
            {
              path:':id',
              name:'Exchange Rate Edit',
              component: ExchangeRateForm,
              props:true
            }
          ]
        },
        // ######## Exchange Rate History ########
        {
          path: 'exchange-history',
          redirect: '/exchange-history',
          name: 'Exchange Rate History',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Exchange Rate History List',
              component: ExchangeRateHistoryIndex
            },
            {
              path: 'exchange-history/form',
              name: 'Exchange Rate History Form',
              component: ExchangeRateHistoryForm
            },
            {
              path:'exchange-history/:id',
              name:'Exchange Rate History Edit',
              component: ExchangeRateHistoryForm,
              props:true
            }
          ]
        },
        // ######## Account Owner ########
        {
          path: 'stocks',
          redirect: '/stocks/account-owner',
          name: 'Account Owner',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-owner',
              name: 'Account Owner List',
              component: AccountOwnerIndex
            },
            {
              path: 'account-owner/form',
              name: 'Account Owner Form',
              component: AccountOwnerForm
            },
            {
              path:'account-owner/:id',
              name:'Account Owner Edit',
              component: AccountOwnerForm,
              props:true
            },
            {
              path:'account-owner/detail/:id',
              name:'Account Owner Detail',
              component: AccountOwnerDetail,
              props:true
            }
          ]
        },
        // ######## Merchant Account ########
        {
          path: 'merchant-account',
          redirect: '/merchant-account/account-master',
          name: 'Merchant Account',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-master',
              name: 'Account Master List',
              component: AccountMasterIndex
            },
            {
              path: 'account-master/form',
              name: 'Account Master Form',
              component: AccountMasterForm
            },
            {
              path:'account-master/:id',
              name:'Account Master Edit',
              component: AccountMasterForm,
              props:true
            },
            {
              path:'account-master/account/:id',
              name:'Account',
              component: AccountIndex,
              props:true
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/merchant-account/account-rule',
          name: 'Account Rule',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-rule',
              name: 'Account Rule List',
              component: AccountRuleIndex
            },
            {
              path: 'account-rule/form',
              name: 'Account Rule Form',
              component: AccountRuleForm
            },{
              path:'account-rule/:id',
              name:'Edit',
              component: AccountRuleForm,
              props:true
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/merchant-account/account-type',
          name: 'Account Type',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-type',
              name: 'Account Type List',
              component: AccountTypeIndex
            },
            {
              path: 'account-type/form',
              name: 'Account Type Form',
              component: AccountTypeForm
            },{
              path:'account-type/:id',
              name:'Account Type Edit',
              component:  AccountTypeForm,
              props:true
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/merchant-account/account-credit',
          name: 'Account Credit',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-credit',
              name: 'Account Credit List',
              component: AccountCreditIndex
            },
            {
              path: 'account-credit/form',
              name: 'Account Credit Form',
              component: AccountCreditForm
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/account-loan',
          name: 'Account Loan',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-loan',
              name: 'Account Loan List',
              component: AccountLoanIndex
            },
            {
              path: 'account-loan/form',
              name: 'Account Loan Form',
              component: AccountLoanForm
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/account-transaction',
          name: 'Account Transaction',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'account-transaction',
              name: 'Account Transaction List',
              component: AccountTransactionIndex
            },
            {
              path: 'account-transaction/form',
              name: 'Account Transaction Form',
              component: AccountTransactionForm
            }
          ]
        },
        // ######## Setup Master ########
        {
          path: 'currency',
          redirect: '/currency/index',
          name: 'Currency',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Currency List',
              component: CurrencyIndex
            },
            {
              path: 'form',
              name: 'Currency Form',
              component: CurrencyForm
            },{
              path:':id',
              name:'Currency Edit',
              component: CurrencyForm,
              props:true
            }
          ]
        },
        {
          path: 'stock-status',
          redirect: '/stock-status',
          name: 'Stock Status',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Stock Status List',
              component: StockStatusIndex
            },
            {
              path: 'form',
              name: 'Stock Status Form',
              component: StockStatusForm
            },{
              path:':id',
              name:'Stock Status Edit',
              component: StockStatusForm,
              props:true
            }
          ]
        },
        {
          path: 'termday',
          redirect: '/termday/index',
          name: 'Term Day',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Term Day List',
              component: TermDayIndex
            },
            {
              path: 'form',
              name: 'Term Day Form',
              component: TermDayForm
            },{
              path:':id',
              name:'Term Day Edit',
              component: TermDayForm,
              props:true
            }
          ]
        },
        {
          path: 'delivery-method',
          redirect: '/delivery-method/index',
          name: 'Delivery Method',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Delivery Method List',
              component: DeliveryMethodIndex
            },
            {
              path: 'form',
              name: 'Delivery Method Form',
              component: DeliveryMethodForm
            },{
              path:':id',
              name:'Delivery Method Edit',
              component: DeliveryMethodForm,
              props:true
            }
          ]
        },
        {
          path: 'restrict-type',
          redirect: '/restrict-type/index',
          name: 'Restrict Type',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Restrict Type List',
              component: RestrictTypeIndex
            },
            {
              path: 'form',
              name: 'Restrict Type Form',
              component: RestrictTypeForm
            },{
              path:':id',
              name:'Restrict Type Edit',
              component: RestrictTypeForm,
              props:true
            }
          ]
        },
        {
          path: 'rule-type',
          redirect: '/rule-type/index',
          name: 'Rule Type',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Rule Type List',
              component: RuleTypeIndex
            },
            {
              path: 'form',
              name: 'Rule Type Form',
              component: RuleTypeForm
            },{
              path:':id',
              name:'Rule Type Edit',
              component: RuleTypeForm,
              props:true
            }
          ]
        },
        {
          path: 'delivery-method-type',
          redirect: '/delivery-method-type/index',
          name: 'Delivery Method Type',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Delivery Method Type List',
              component: DeliveryMethodTypeIndex
            },
            {
              path: 'form',
              name: 'Delivery Method Type Form',
              component: DeliveryMethodTypeForm
            },{
              path:':id',
              name:'Delivery Method Type Edit',
              component: DeliveryMethodTypeForm,
              props:true
            }
          ]
        },
        {
          path: 'transaction-rule',
          redirect: '/transaction-rule/index',
          name: 'Transaction Rule',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Transaction Rule List',
              component: TransactionRuleIndex
            },
            {
              path: 'form',
              name: 'Transaction Rule Form',
              component: TransactionRuleForm
            },{
              path:':id',
              name:'Transaction Rule Edit',
              component: TransactionRuleForm,
              props:true
            }
          ]
        },
        {
          path: 'merchant-account',
          redirect: '/merchant-account/transaction-charge-fee',
          name: 'Transaction Charge Fee',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'transaction-charge-fee',
              name: 'Transaction Charge Fee List',
              component: TransactionChargeFeeIndex
            },
            {
              path: 'transaction-charge-fee/form',
              name: 'Transaction Charge Fee Form',
              component: TransactionChargeFeeForm
            },{
              path:'transaction-charge-fee/:id',
              name:'Transaction Charge Fee Edit',
              component: TransactionChargeFeeForm,
              props:true
            }
          ]
        },
        {
          path: 'amount-top-up',
          redirect: '/amount-top-up/index',
          name: 'Amount Top Up',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Amount Top Up List',
              component: AmountTopUpIndex
            },
            {
              path: 'form',
              name: 'Amount Top Up Form',
              component: AmountTopUpForm
            },{
              path:':id',
              name:'Amount Top Up Edit',
              component: AmountTopUpForm,
              props:true
            }
          ]
        },
        {
          path: 'interest-rate-period',
          redirect: '/interest-rate-period/index',
          name: 'Interest Rate Period',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Interest Rate Period List',
              component: InterestRatePeriodIndex
            },
            {
              path: 'form',
              name: 'Interest Rate Period Form',
              component: InterestRatePeriodForm
            },{
              path:':id',
              name:'Interest Rate Period Edit',
              component:  InterestRatePeriodForm,
              props:true
            }
          ]
        },
        {
          path: 'interest-method',
          redirect: '/interest-method/index',
          name: 'Interest Method Period',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Interest Method List',
              component: InterestMethodIndex
            },
            {
              path: 'form',
              name: 'Interest Method Form',
              component: InterestMethodForm
            },{
              path:':id',
              name:'Interest Method Edit',
              component:  InterestMethodForm,
              props:true
            }
          ]
        },
        {
          path: 'country',
          redirect: '/country/index',
          name: 'Country',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Country List',
              component: CountryIndex
            },
            {
              path: 'form',
              name: 'Country Form',
              component: CountryForm
            },{
              path:':id',
              name:'Country Edit',
              component:  CountryForm,
              props:true
            }
          ]
        },
        {
          path: 'payment-cycle',
          redirect: '/payment-cycle/index',
          name: 'Payment Cycle',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Payment Cycle List',
              component: PaymentCycleIndex
            },
            {
              path: 'form',
              name: 'Payment Cycle Form',
              component: PaymentCycleForm
            },{
              path:':id',
              name:'Payment Cycle Edit',
              component:  PaymentCycleForm,
              props:true
            }
          ]
        },
        {
          path: 'fly-location',
          redirect: '/fly-location/index',
          name: 'Fly Location',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Fly Location List',
              component: FlyLocationIndex
            },
            {
              path: 'form',
              name: 'Fly Location Form',
              component: FlyLocationForm
            },{
              path:':id',
              name:'Fly Location Edit',
              component:  FlyLocationForm,
              props:true
            }
          ]
        },
        {
          path: 'product-loan',
          redirect: '/product-loan/index',
          name: 'Product Loan',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Product Loan List',
              component: ProductLoanIndex
            },
            {
              path: 'form',
              name: 'Product Loan Form',
              component: ProductLoanForm
            },{
              path:':id',
              name:'Product Loan Edit',
              component:  ProductLoanForm,
              props:true
            }
          ]
        },
        {
          path: 'booking-type',
          redirect: '/booking-type/index',
          name: 'Booking Type',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Booking Type List',
              component: BookingTypeIndex
            },
            {
              path: 'form',
              name: 'Booking Type Form',
              component: BookingTypeForm
            },{
              path:':id',
              name:'Booking Type Edit',
              component:  BookingTypeForm,
              props:true
            }
          ]
        },
        {
          path: 'bookings',
          redirect: '/bookings/index',
          name: 'Bookings',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Bookings List',
              component: BookingIndex
            },
            {
              path: 'form',
              name: 'Bookings Form',
              component: BookingForm
            },{
              path:':id',
              name:'Bookings Edit',
              component:  BookingForm,
              props:true
            }
          ]
        },
        // Vouchers
        {
          path: 'vouchers',
          redirect: '/vouchers/index',
          name: 'Vouchers',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Vouchers List',
              component: VoucherIndex
            },
            {
              path: 'form',
              name: 'Vouchers Form',
              component: VoucherForm
            },{
              path:':id',
              name:'Vouchers Edit',
              component:  VoucherForm,
              props:true
            }
          ]
        },
        // Buy Voip
        {
          path: 'buy-voip',
          redirect: '/buy-voip/index',
          name: 'Buy Voip',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Buy Voip List',
              component: BuyVoipIndex
            },
            {
              path: 'form',
              name: 'Buy Voip Form',
              component: BuyVoipForm
            },{
              path:':id',
              name:'Buy Voip Edit',
              component:  BuyVoipForm,
              props:true
            }
          ]
        },
        // Category
        {
          path: 'category',
          redirect: '/category',
          name: 'Category',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Category List',
              component: CategoryIndex
            },
            {
              path: 'form',
              name: 'Category Form',
              component: CategoryForm
            },{
              path:':id',
              name:'Category Edit',
              component:  CategoryForm,
              props:true
            }
          ]
        },
        // Product
        {
          path: 'product',
          redirect: '/product',
          name: 'Product',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Product List',
              component: ProductIndex
            },
            {
              path: 'form',
              name: 'Product Form',
              component: ProductForm
            },{
              path:':id',
              name:'Product Edit',
              component:  ProductForm,
              props:true
            }
          ]
        },
        // Buy Vouchers
        {
          path: 'buy-voucher',
          redirect: '/buy-voucher/index',
          name: 'Buy Voucher',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Buy Voucher List',
              component: BuyVoucherIndex
            },
            {
              path: 'form',
              name: 'Buy Voucher Form',
              component: BuyVoucherForm
            },{
              path:':id',
              name:'Buy Voucher Edit',
              component:  BuyVoucherForm,
              props:true
            },{
              path:'view/:id',
              name:'Buy Voucher View',
              component:  BuyVoucherView,
              props:true
            }
          ]
        },
        // Send Money
        {
          path: 'send-money',
          redirect: '/send-money/index',
          name: 'Send Money',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Send Money List',
              component: SendMoneyIndex
            },
            {
              path: 'form',
              name: 'Send Money Form',
              component: SendMoneyForm
            },{
              path:':id',
              name:'Send Money Edit',
              component:  SendMoneyForm,
              props:true
            }
          ]
        },
        // Transfer Money
        {
          path: 'transfer-money',
          redirect: '/transfer-money/index',
          name: 'Transfer Money',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Transfer Money List',
              component: TransferMoneyIndex
            },
            {
              path: 'form',
              name: 'Transfer Money Form',
              component: TransferMoneyForm
            },{
              path:':id',
              name:'Transfer Money Edit',
              component:  TransferMoneyForm,
              props:true
            }
          ]
        },
        // Setting
        {
          path: 'setting',
          redirect: '/setting/index',
          name: 'Settings',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'configuration',
              name: 'Configuration',
              component: ConfigurationIndex
            }
          ]
        },
        // order
        {
          path: 'order',
          redirect: '/order/index',
          name: 'Orders',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '/order/order-list',
              name: 'Order List',
              component: OrderIndex
            }
          ]
        },
        // Users
        {
          path: 'user',
          redirect: '/user/index',
          name: 'Users',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Users List',
              component: UserIndex
            },
            {
              path: 'form',
              name: 'Users Form',
              component: UserForm
            },{
              path:':id',
              name:'Users Edit',
              component:  UserForm,
              props:true
            }
          ]
        },
        // User Groups
        {
          path: 'user-group',
          redirect: '/user-group/index',
          name: 'User Groups',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'User Groups List',
              component: UserGroupIndex
            },
            {
              path: 'form',
              name: 'User Groups Form',
              component: UserGroupForm
            },{
              path:':id',
              name:'User Groups Edit',
              component:  UserGroupForm,
              props:true
            }
          ]
        },
        // User Groups
        {
          path: 'live-chat',
          redirect: '/live-chat',
          name: 'Chats',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              name: 'Live Chat List',
              component: LiveChat
            }
          ]
        },
        // ######## Widgets #######
        {
          path: 'widgets',
          name: 'Widgets',
          component: Widgets
        },
        {
          path: 'components',
          redirect: '/components/buttons',
          name: 'Components',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'buttons',
              name: 'Buttons',
              component: Buttons
            },
            {
              path: 'social-buttons',
              name: 'Social Buttons',
              component: SocialButtons
            },
            {
              path: 'cards',
              name: 'Cards',
              component: Cards
            },
            {
              path: 'forms',
              name: 'Forms',
              component: Forms
            },
            {
              path: 'modals',
              name: 'Modals',
              component: Modals
            },
            {
              path: 'switches',
              name: 'Switches',
              component: Switches
            },
            {
              path: 'tables',
              name: 'Tables',
              component: Tables
            }
          ]
        },
        {
          path: 'icons',
          redirect: '/icons/font-awesome',
          name: 'Icons',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'font-awesome',
              name: 'Font Awesome',
              component: FontAwesome
            },
            {
              path: 'simple-line-icons',
              name: 'Simple Line Icons',
              component: SimpleLineIcons
            }
          ]
        }
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/p404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
        {
          path: 'register',
          name: 'Register',
          component: Register
        }
      ]
    }
  ]
})
