export default {
  items: [
    {
      name: 'Dashboard',
      url: '/dashboard',
      icon: 'icon-speedometer',
      badge: {
        variant: 'primary',
        // text: 'NEW'
      }
    },
    {
      title: true,
      name: 'General'
    },
    {
      name: 'Merchant Account',
      url: '/merchant-account/account-rule',
      icon: 'fa fa-user-circle-o',
      children: [
        {
          name: 'Account Master',
          url: '/merchant-account/account-master',
          icon: 'icon-star'
        },
        // {
        //   name: 'Account Credit',
        //   url: '/merchant-account/account-credit',
        //   icon: 'icon-star'
        // },
        // {
        //   name: 'Account Loan',
        //   url: '/merchant-account/account-loan',
        //   icon: 'icon-star'
        // },
        {
          name: 'Transaction Rule',
          url: '/transaction-rule',
          icon: 'icon-star'
        },
        {
          name: 'Loan Account Rule',
          url: '/merchant-account/account-rule',
          icon: 'icon-star'
        },
        {
          name: 'Account Type',
          url: '/merchant-account/account-type',
          icon: 'icon-star'
        },
        {
          name: 'Transaction Fee',
          url: '/merchant-account/transaction-charge-fee',
          icon: 'icon-star'
        }
        // {
        //   name: 'Transaction',
        //   url: '/merchant-account/account-transaction',
        //   icon: 'icon-star'
        // }
      ]
    },
    {
      name: 'Money',
      url: '/money',
      icon: 'fa fa-money',
      children: [
        {
          name: 'Send Money',
          url: '/send-money',
          icon: 'icon-star'
        },
        {
          name: 'Transfer Money',
          url: '/transfer-money',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Vouchers',
      url: '/vouchers',
      icon: 'fa fa-scribd',
      children: [
        {
          name: 'Vouchers',
          url: '/vouchers',
          icon: 'icon-star'
        },
        {
          name: 'Buy Vouchers',
          url: '/buy-voucher',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Voips',
      url: '/voip',
      icon: 'fa fa-scribd',
      children: [
        {
          name: 'Buy Voip',
          url: '/buy-voip',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Catalog',
      url: '/category',
      icon: 'icon-menu',
      children: [
        {
          name: 'Category',
          url: '/category',
          icon: 'icon-star'
        },
        {
          name: 'Product',
          url: '/product',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Stock Balance',
      url: '/stocks',
      icon: 'icon-briefcase',
      children: [
        {
          name: 'Back Account',
          url: '/stocks/bank-account',
          icon: 'icon-star'
        },
        {
          name: 'Account Owner',
          url: '/stocks/account-owner',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Currency',
      url: '/money',
      icon: 'fa fa-money',
      children: [
        {
          name: 'Exchange Rate',
          url: '/exchange-rate',
          icon: 'icon-star'
        },
        {
          name: 'Exchange History',
          url: '/exchange-history',
          icon: 'icon-star'
        },
        {
          name: 'Currency',
          url: '/currency',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Billing Management',
      url: '/icons',
      icon: 'fa fa-random',
      children: [
        {
          name: 'Product Loan',
          url: '/product-loan',
          icon: 'icon-star'
        },
        {
          name: 'Fly Location',
          url: '/fly-location',
          icon: 'icon-star'
        },
        {
          name: 'Term Day',
          url: '/termday',
          icon: 'icon-star'
        },
        {
          name: 'Delivery Method',
          url: '/delivery-method',
          icon: 'icon-star'
        },
        {
          name: 'Delivery Method Type',
          url: '/delivery-method-type',
          icon: 'icon-star'
        },
        {
          name: 'Amount Topup',
          url: '/amount-top-up',
          icon: 'icon-star'
        },
        {
          name: 'Interest Rate Period',
          url: '/interest-rate-period',
          icon: 'icon-star'
        },
        {
          name: 'Interest Method',
          url: '/interest-method',
          icon: 'icon-star'
        },
        {
          name: 'Country',
          url: '/country',
          icon: 'icon-star'
        },
        {
          name: 'Payment Cycle',
          url: '/payment-cycle',
          icon: 'icon-star'
        },
        {
          name: 'Restrict Type',
          url: '/restrict-type',
          icon: 'icon-star'
        },
        {
          name: 'Rule Type',
          url: '/rule-type',
          icon: 'icon-star'
        },
        {
          name: 'Stock Status',
          url: '/stock-status',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Bookings',
      url: '/icons',
      icon: 'icon-book-open',
      children: [
        {
          name: 'Booking Type',
          url: '/booking-type',
          icon: 'icon-star'
        },
        {
          name: 'Bookings',
          url: '/bookings',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Order',
      url: '/order',
      icon: 'icon-book-open',
      children: [
        {
          name: 'Order List',
          url: '/order/order-list',
          icon: 'icon-star'
        }
      ]
    },
    // {
    //   name: 'Components',
    //   url: '/components',
    //   icon: 'icon-puzzle',
    //   children: [
    //     {
    //       name: 'Buttons',
    //       url: '/components/buttons',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Social Buttons',
    //       url: '/components/social-buttons',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Cards',
    //       url: '/components/cards',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Forms',
    //       url: '/components/forms',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Modals',
    //       url: '/components/modals',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Switches',
    //       url: '/components/switches',
    //       icon: 'icon-puzzle'
    //     },
    //     {
    //       name: 'Tables',
    //       url: '/components/tables',
    //       icon: 'icon-puzzle'
    //     }
    //   ]
    // },
    // {
    //   name: 'Icons',
    //   url: '/icons',
    //   icon: 'icon-star',
    //   children: [
    //     {
    //       name: 'Font Awesome',
    //       url: '/icons/font-awesome',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Simple Line Icons',
    //       url: '/icons/simple-line-icons',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Buttons',
    //       url: '/components/buttons',
    //       icon: 'icon-puzzle'
    //     },
    //   ]
    // },
    // {
    //   name: 'Widgets',
    //   url: '/widgets',
    //   icon: 'icon-calculator',
    //   badge: {
    //     variant: 'danger',
    //     text: 'NEW'
    //   }
    // },
    // {
    //   name: 'Charts',
    //   url: '/charts',
    //   icon: 'icon-pie-chart'
    // },
    // {
    //   divider: true
    // },
    // {
    //   title: true,
    //   name: 'Security'
    // },
    {
      name: 'Users',
      url: '/user',
      icon: 'icon-user',
      children: [
        {
          name: 'Users',
          url: '/user',
          icon: 'icon-star'
        },
        {
          name: 'User Groups',
          url: '/user-group',
          icon: 'icon-star'
        },
        // {
        //   name: 'Group Roles',
        //   url: '/user-group-role',
        //   icon: 'icon-star'
        // }
      ]
    },
    {
      name: 'Settings',
      url: '/setting',
      icon: 'icon-settings',
      children: [
        {
          name: 'Configuration',
          url: '/setting/configuration',
          icon: 'icon-star'
        }
      ]
    },
    {
      name: 'Live Chats',
      url: '/live-chat',
      icon: 'fa fa-comments-o'
    },
    // {
    //   name: 'Pages',
    //   url: '/pages',
    //   icon: 'icon-star',
    //   children: [
    //     {
    //       name: 'Login',
    //       url: '/pages/login',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Register',
    //       url: '/pages/register',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Error 404',
    //       url: '/pages/404',
    //       icon: 'icon-star'
    //     },
    //     {
    //       name: 'Error 500',
    //       url: '/pages/500',
    //       icon: 'icon-star'
    //     }
    //   ]
    // }
  ]
}
