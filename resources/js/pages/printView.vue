<template>
    <Teleport to="body">
      <div id="printable-area">
        <h1>BON DE LIVRAISON</h1>
        <div class="header">
          <div class="header-left">
            <img src="/image/logo-small.png" alt="" />
  
            <p>
              <!-- 86 Bd Félix Faure, 93300 Aubervilliers, France -->
              86 Boulevard Felix Faure, <br />
              93300 Aubervilliers, France
              <br />
              07 58 17 06 52
              <br />
              07 53 33 51 44
              <br />
              06 27 92 09 99
            </p>
          </div>
          <div class="header-right">
            <h3>Client</h3>
  
            <p>
              <strong>Prénom</strong>: {{ order.first_name }}
              <br />
              <strong>Nom</strong>: {{ order.last_name }}
              <br />
              <strong>Tel</strong>: {{ order.telephone_number }}
              <br />
              <strong>Addresse</strong>: {{ order.address }}
              <br />
              <strong>Code Postal</strong>: {{ order.zip_code }}
              <br />
              <strong>Ville</strong>: {{ order.would }}
              <br />
              <strong>Etage</strong>: {{ order.floor_number }}
              <br />
              <strong>L'heure de livraison</strong>: {{ order?.other?.delivery_time }}
            </p>
          </div>
        </div>
  
        <div class="sub-header">
          <div class="sub-header-left">
            <h4>Date de Commande</h4>
  
            <p>
              {{
                new Date(order.order_date).toLocaleDateString('fr-FR', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric'
                })
              }}
            </p>
          </div>
          <div class="sub-header-center">
            <h4>Date de Livraison & Jour</h4>
  
            <p>
              {{
                new Date(order.delivery_date).toLocaleDateString('fr-FR', {
                  year: 'numeric',
                  month: 'long',
                  day: 'numeric'
                })
              }}
              ({{ getDate(order.day) }})
            </p>
          </div>
          <div class="sub-header-right">
            <h4>N° de Livraison</h4>
  
            <p>{{ order.delivery_number }}</p>
          </div>
        </div>
  
        <div class="data-table">
          <table>
            <thead>
              <tr>
                <th>Référence</th>
                <th>Designation</th>
                <th>Qté</th>
                <th>Prix unitaire</th>
                <th>Prix HT</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in order.items" :key="index">
                <td>{{ item.reference }}</td>
                <td>{{ item.designation }}</td>
                <td>{{ item.qty }}</td>
                <td>€{{ item.unit_price }}</td>
                <td>€{{ item.total_price }}</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4">Total HT</td>
                <td>€{{ total }}</td>
              </tr>
            </tfoot>
          </table>
        </div>
  
        <div class="footer">
          <div class="footer-left">
            <div class="footer-left-payment">
              <h4>Mode de paiement</h4>
              <h2>{{ order.other?.payment_method || 'En espèces' }}</h2>
            </div>
          </div>
  
          <div class="footer-right">
            <div class="footer-right-signature">
              <small>J'ai bien reçu ma commande</small>
              <h4>Signature du client</h4>
            </div>
          </div>
        </div>
        <!-- due total -->
        <h3 class="due-total-note" v-if="order.other?.due > 0">
          Paiement total reçu {{ order.other?.paid }} € et reste à payer {{ order.other?.due }} €
        </h3>
  
        <h3 class="due-total-note" v-if="order.other?.due == 0">
          Paiement total reçu {{ order.other?.paid }} €
        </h3>
  
        <h3 class="due-total-note" v-if="order.other?.due < 0">
          Paiement total reçu {{ order.other?.paid }} € et reste à rendre
          {{ order.other?.due * -1 }} €
        </h3>
  
        <div class="note" v-if="order.other?.note">
          <h4>Note</h4>
          <p>{{ order.other?.note }}</p>
        </div>
      </div>
    </Teleport>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  
  export default {
    name: 'PrintView',
    computed: {
      total() {
        return this.order?.items
          ?.reduce((acc, item) => {
            return acc + parseFloat(item.total_price)
          }, 0)
          .toFixed(2)
      }
    },
    setup() {
      const order = ref({})
  
      const route = useRoute()
      const router = useRouter()
  
      fetch(`/api/?id=${route.params.id}`)
        .then((res) => res.json())
        .then((json) => {
          if (json.status === 'error') {
            alert(json.message)
            localStorage.clear()
            document.cookie.split(';').forEach(function (c) {
              document.cookie = c
                .replace(/^ +/, '')
                .replace(/=.*/, '=;expires=' + new Date().toUTCString() + ';path=/')
            })
            // redirect to login
            window.location.href = '/login'
            return
          }
          order.value = json.payload.data
  
          // convert id to 00000001 format
          order.value.delivery_number = 'BD-' + order.value.id.toString().padStart(8, '0')
  
          setTimeout(() => {
            window.print()
          }, 1000)
  
          window.onafterprint = () => {
            router.go(-1)
          }
        })
  
      const getDate = (day) => {
        switch (day) {
          case 'Monday':
            return 'Lundi'
          case 'Tuesday':
            return 'Mardi'
          case 'Wednesday':
            return 'Mercredi'
          case 'Thursday':
            return 'Jeudi'
          case 'Friday':
            return 'Vendredi'
          case 'Saturday':
            return 'Samedi'
          case 'Sunday':
            return 'Dimanche'
          default:
            return day
        }
      }
  
      return {
        order,
        getDate
      }
    }
  }
  </script>
  
  <style lang="scss">
  #printable-area {
    display: block;
    max-width: 800px;
    min-height: 100vh;
    margin: 0.5in auto;
    padding: 0.5in;
    box-sizing: border-box;
    font-family: sans-serif;
    box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
    font-size: 16px;
    background-color: #fff;
  
    h1 {
      text-align: center;
      font-size: 30px;
      margin-bottom: 0.2in;
    }
  
    .header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.2in;
      gap: 0.5in;
  
      // two boxes on the left and right
      .header-left,
      .header-right {
        width: 50%;
        border: 1.5px solid #000;
        padding: 0.2in;
        font-weight: bold;
        position: relative;
        margin-top: 1rem;
        line-height: 1.4;
  
        p {
          margin: 0;
          font-weight: normal;
        }
  
        strong {
          min-width: 100px;
          display: inline-block;
          font-weight: bold;
        }
  
        @media print {
          width: 50vw;
        }
      }
  
      .header-left p {
        font-weight: bold;
      }
  
      .header-left img {
        width: 100px;
        height: auto;
        margin-bottom: 1rem;
      }
  
      .header-right h3 {
        position: absolute;
        top: -0.125in;
        background-color: white;
        padding: 0 0.1in;
        margin-left: -0.1in;
      }
    }
  
    .sub-header {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.3in;
      gap: 0.25in;
  
      // three boxes on the left, center and right
      .sub-header-left,
      .sub-header-center,
      .sub-header-right {
        width: 33%;
        border: 1.5px solid #000;
        padding: 0.2in;
        font-weight: bold;
        position: relative;
        margin-top: 1rem;
  
        @media print {
          width: 33vw;
        }
  
        p {
          text-align: center;
        }
      }
  
      .sub-header-left h4,
      .sub-header-center h4,
      .sub-header-right h4 {
        font-size: 14px;
        position: absolute;
        top: -0.1in;
        margin-left: -0.1in;
        background-color: white;
        padding: 0 0.1in;
      }
    }
  
    .data-table {
      table {
        width: 100%;
        border-collapse: collapse;
        border: 1.5px solid #000;
        margin-bottom: 0.2in;
        table-layout: auto;
  
        thead {
          tr {
            th {
              border: 1.5px solid #000;
              padding: 8px 16px;
              font-weight: bold;
              background-color: rgba(0, 0, 0, 0.2);
  
              &:nth-child(4) {
                padding: 10px 0;
              }
            }
          }
        }
  
        tbody {
          tr {
            td {
              border: 1.5px solid #000;
              padding: 8px 16px;
  
              &:first-child {
                width: 10%;
              }
  
              &:nth-child(2) {
                width: 50%;
              }
  
              &:nth-child(3) {
                width: 10%;
                text-align: center;
              }
  
              &:nth-child(4) {
                width: 15%;
                text-align: right;
              }
  
              &:last-child {
                width: 15%;
                text-align: right;
              }
            }
  
            &:nth-child(even) {
              background-color: rgba(0, 0, 0, 0.05);
            }
          }
        }
  
        tfoot {
          tr {
            td {
              border: 1.5px solid #000;
              padding: 8px 16px;
              font-weight: bold;
  
              &:last-child {
                text-align: right;
              }
            }
          }
        }
      }
    }
  
    .footer {
      display: flex;
      justify-content: space-between;
      gap: 0.5in;
      margin-top: 0.2in;
  
      // two boxes on the left and right
      .footer-left,
      .footer-right {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50%;
        border: 1.5px solid #000;
        padding: 0.2in;
        font-weight: bold;
        position: relative;
        margin-top: 1rem;
        height: 0.5in;
        @media print {
          width: 50vw;
        }
      }
  
      .footer-left h4,
      .footer-right h4 {
        font-size: 14px;
        position: absolute;
        top: -0.1in;
        background-color: white;
        padding: 0 0.1in;
      }
  
      .footer-right {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.5in;
  
        &-signature {
          display: flex;
          justify-content: center;
          align-items: center;
          width: 100%;
        }
  
        small {
          font-size: 12px;
          color: rgba(0, 0, 0, 0.5);
          position: absolute;
          bottom: 0.1in;
          text-align: center;
          width: 90%;
          left: 5%;
          right: 5%;
        }
      }
    }
  }
  
  .due-total-note {
    text-align: center;
    margin-top: 0.2in;
  }
  
  .note {
    margin-top: 0.2in;
    border: 1.5px solid #000;
    padding: 0.2in;
    font-weight: bold;
    position: relative;
    line-height: 1.4;
  
    h4 {
      font-size: 14px;
      position: absolute;
      top: -0.1in;
      background-color: white;
      padding: 0 0.1in;
    }
  
    p {
      margin: 0;
      font-weight: normal;
    }
  }
  
  @media print {
    // sizes
    @page {
      margin: 0.8in;
  
      @top-right {
        content: 'Page ' counter(pageNumber);
      }
    }
  
    #printable-area {
      position: absolute;
      left: 0;
      top: 0;
      margin: 0;
      box-shadow: none;
      padding: 0;
      font-size: 16px;
    }
  
    #app {
      display: none;
    }
  
    body * {
      background-color: #fff;
    }
  }
  </style>
  