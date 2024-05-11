<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

const hours = ref([]);
for (let i = 0; i < 24; i++) {
    hours.value.push(`${i < 10 ? "0" + i : i}.00h`);
}

const items = ref([
    {
        reference: "",
        designation: "",
        qty: 0,
        unit_price: 0,
        total_price: 0,
    },
]);

const totalHT = computed(() => {
    return items.value.reduce((acc, curr) => acc + curr.total_price, 0);
});

const addQty = (item) => {
    item.qty++;
};

const subQty = (item) => {
    if (item.qty > 0) {
        item.qty--;
    }
};

const getTotalPrice = (item) => {
    item.total_price = item.qty * item.unit_price;
    return item.total_price;
};

const removeitem = (index) => {
    items.value.splice(index, 1);
};

const additem = () => {
    items.value.push({
        reference: "",
        designation: "",
        qty: 0,
        unit_price: 0,
        total_price: 0,
        totalHT: totalHT,
    });
};

const other = ref({
    payment_method: "",
    paid: "",
    delivery_form: "",
    delivery_to: "",
    note: "",
});

const form = ref({
    order_date: "",
    delivery_date: "",
    day: "",
    delivery_number: "BD-1692635651325",
    first_name: "",
    last_name: "",
    telephone_number: "",
    address: "",
    zip_code: "",
    would: "",
    floor_number: "",
    items: items,
    other: other.value,
});


const store = async () => {
    try {
        const response = await axios.post("/api/add-order", form.value);
        window.location.href = '/'
    } catch (err) {
        console.error("Error submitting form:", err);
    }
};

const update = async () => {
    try {
        const response = await axios.get(`/update-order/{id}`, form.value);
        resetForm();
        alert("Order Successfully Added");
    } catch (err) {
        console.error("Error submitting form:", err);
    }
};

const submit = async () => {
    if(route.params.id){
        update();
    }else{
        store();
    }
}

</script>

<template>
    <div class="sidebar">
        <div class="m-sidebar__logo">
            <img src="../images/logo.png" alt="" />
        </div>
        <RouterLink to="/entry" class="m-sidebar_new_entry_btn">+ Ajouter</RouterLink>
        <RouterLink to="/" class="m-sidebar_link"><p>Entrées</p></RouterLink>
    </div>
    <div class="content">
        <div class="new-entry">
            <p class="m-heading">Ajouter une nouvelle entrée</p>
            <form class="m-new-entry-form">
                <div class="top">
                    <div class="input_wrap">
                        <label for="order_date">Date de Commande</label>
                        <input
                            required=""
                            placeholder="Date de Commande"
                            class="profile_input"
                            type="date"
                            id="order_date"
                            v-model="form.order_date"
                        />
                    </div>
                    <div class="input_wrap">
                        <label for="delivery_date">Date de Livraison</label>
                        <input
                            required=""
                            placeholder="Date de Livraison"
                            class="profile_input"
                            type="date"
                            id="delivery_date"
                            v-model="form.delivery_date"
                        />
                    </div>
                    <div class="input_wrap">
                        <label for="day">Jour</label>
                        <input
                            required=""
                            placeholder="Jour"
                            class="profile_input"
                            type="text"
                            id="day"
                            v-model="form.day"
                        />
                    </div>
                </div>
                <div class="information_box">
                    <div class="mdmebules_information">
                        <div class="mdmebules_container">
                            <h3 class="m-heading">Bd Meubles</h3>
                            <div class="mdmebules_address">
                                <p>
                                    86 Boulevard Felix Faure, 93300
                                    Aubervilliers, France
                                </p>
                                <p>07 58 17 06 52</p>
                                <p>07 53 33 51 44</p>
                                <p>06 27 92 09 99</p>
                            </div>
                        </div>
                    </div>
                    <div class="client_information">
                        <div class="client_information_container">
                            <h1 class="m-heading">Client</h1>
                            <div class="client_information_flex_box">
                                <input
                                    required=""
                                    placeholder="Prénom"
                                    type="text"
                                    class="profile_input"
                                    v-model="form.first_name"
                                />
                                <input
                                    required=""
                                    placeholder="Nom"
                                    type="text"
                                    class="profile_input"
                                    v-model="form.last_name"
                                />
                            </div>
                            <div class="client_information_flex_box">
                                <input
                                    required=""
                                    placeholder="Téléphone"
                                    type="text"
                                    class="profile_input"
                                    v-model="form.telephone_number"
                                />
                                <input
                                    required=""
                                    placeholder="Adresse"
                                    type="text"
                                    class="profile_input"
                                    v-model="form.address"
                                />
                            </div>
                            <div class="client_information_flex_box">
                                <input
                                    required=""
                                    placeholder="Code Postal"
                                    type="text"
                                    class="small_input"
                                    v-model="form.zip_code"
                                />
                                <input
                                    required=""
                                    placeholder="Ville"
                                    type="text"
                                    class="small_input"
                                    v-model="form.would"
                                />
                                <input
                                    required=""
                                    placeholder="Etage"
                                    type="text"
                                    class="small_input"
                                    v-model="form.floor_number"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="m-new-entry-list">
                <div class="contaner">
                    <form>
                        <table>
                            <tr class="table_heading">
                                <th>Référence</th>
                                <th>Designation</th>
                                <th>Qté</th>
                                <th>Prix unitaire</th>
                                <th>Prix Total HT</th>
                            </tr>

                            <tr
                                class="table_data"
                                v-for="(item, index) in items"
                                :key="index"
                            >
                                <td>
                                    <input
                                        required=""
                                        placeholder="Référence"
                                        type="text"
                                        v-model="item.reference"
                                    />
                                </td>
                                <td>
                                    <input
                                        required=""
                                        placeholder="Designation"
                                        type="text"
                                        v-model="item.designation"
                                    />
                                </td>
                                <td class="qty">
                                    <button
                                        class="table_data_button"
                                        @click="subQty(item)"
                                    >
                                        -
                                    </button>
                                    <input
                                        required=""
                                        placeholder="Qté"
                                        type="number"
                                        v-model="item.qty"
                                    />
                                    <button
                                        class="table_data_button"
                                        @click="addQty(item)"
                                    >
                                        +
                                    </button>
                                </td>
                                <td>
                                    <input
                                        required=""
                                        placeholder="Prix unitaire"
                                        type="number"
                                        v-model="item.unit_price"
                                    />
                                </td>
                                <td>
                                    <input
                                        required=""
                                        placeholder="Prix Total HT"
                                        type="number"
                                        :value="getTotalPrice(item)"
                                    /><button @click="removeitem(index)">
                                        x
                                    </button>
                                </td>
                            </tr>

                            <tr class="table_data">
                                <td
                                    colspan="4"
                                    style="text-align: end; padding-right: 15px"
                                >
                                    Total HT
                                </td>
                                <td>
                                    <input
                                        required=""
                                        placeholder="Total HT"
                                        type="number"
                                        disabled=""
                                        v-model="totalHT"
                                    />
                                </td>
                            </tr>
                        </table>
                    </form>
                    <div class="button">
                        <button class="button_add" @click="additem">
                            Ajouter
                        </button>
                    </div>
                    <div class="note">
                        <div class="row">
                            <div class="col">
                                <label for="payment-method"
                                    >Méthode de paiement</label
                                >
                                <select
                                    name="payment-method"
                                    id="payment-method"
                                    v-model="other.payment_method"
                                >
                                    <option value="En especes">
                                        En especes
                                    </option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Virament">Virament</option>
                                    <option value="paiement en 4 Fois">
                                        paiement en 4 Fois
                                    </option>
                                </select>
                                <label for="paid">Payé</label>
                                <input
                                    type="number"
                                    name="paid"
                                    id="paid"
                                    v-model="other.paid"
                                /><br />
                                <label for="delivery-time"
                                    >L'heure de livraison</label
                                >
                                <div class="select-group">
                                    <select
                                        name="delivery-from"
                                        id="delivery-from"
                                        v-model="other.delivery_form"
                                    >
                                        <option
                                            v-for="hour in hours"
                                            :key="hour"
                                            :value="hour"
                                        >
                                            {{ hour }}
                                        </option>
                                    </select>
                                    <select
                                        name="delivery-to"
                                        id="delivery-to"
                                        v-model="other.delivery_to"
                                    >
                                        <option
                                            v-for="hour in hours"
                                            :key="hour"
                                            :value="hour"
                                        >
                                            {{ hour }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <label for="note">Note</label
                                ><textarea
                                    name="note"
                                    id="note"
                                    cols="30"
                                    rows="10"
                                    v-model="other.note"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <p></p>
                <button type="submit" class="submit_btn" @click="submit">
                    Submit
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.m-sidebar__logo img {
    width: 100px;
    height: 98px;
    margin-left: 20px;
}

.sidebar {
    width: 328px;
    height: 100vh;
    position: fixed;
    border-right: 0.5px solid #c0bbbb;
    background-color: #fff;
}

.sidebar .m-sidebar_link{
    width: 300px;
    height: 50px;
    color: #212020;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 30px;
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: 120%;
}

.content {
    margin-left: 328px;
    background-color: #eae6e6;
}

.m-sidebar_new_entry_btn {
    margin-left: 40px;
    margin-bottom: 50px;
    width: 251px;
    height: 50px;
    border-radius: 12px;
    background: #0f8677;
    color: #fff;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 30px;
    font-size: 18px;
    font-style: normal;
    font-weight: 600;
    line-height: 120%;
}
.profile_input {
    width: -webkit-fill-available;
    height: 48px;
    border-radius: 8px;
    background: #f8f8f8;
    border: none;
    padding: 0 10px;
    color: #333;
    font-size: 16px;
    font-weight: 400;
    line-height: 100%;
}
.new-entry {
    padding: 30px;
}
.m-heading {
    color: #333;
    font-size: 24px;
    font-weight: 700;
    line-height: 100%;
}
.m-new-entry {
    padding: 30px;
}
.m-new-entry-form {
    margin-top: 30px;
}
.top {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: start;
    gap: 25px;
}
.input_wrap {
    width: 100%;
}
label {
    display: block;
    color: #333;
    font-size: 16px;
    font-weight: 700;
    line-height: 100%;
    margin-bottom: 0.5rem;
}

.profile_input {
    width: -webkit-fill-available;
    height: 48px;
    border-radius: 8px;
    background: #f8f8f8;
    border: none;
    padding: 0 10px;
    color: #333;
    font-size: 16px;
    font-weight: 400;
    line-height: 100%;
}

.small_input {
    width: 237px;
    height: 48px;
    border-radius: 8px;
    background: #f8f8f8;
    border: none;
    padding: 0 10px;
    color: #333;
    font-size: 16px;
    font-weight: 400;
    line-height: 100%;
}

.information_box {
    margin-top: 25px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: start;
    gap: 25px;
}
.mdmebules_information {
    width: 30%;
    height: 311px;
    border-radius: 26px;
    background: #fff;
}

.client_information {
    border-radius: 26px;
    background: #fff;
    width: 70%;
    height: 311px;
}
.mdmebules_container {
    padding: 30px;
}
.mdmebules_address {
    margin-top: 10px;
}

.client_information_container {
    padding: 30px;
}

.m-heading {
    color: #333;
    font-size: 24px;
    font-weight: 700;
    line-height: 100%;
}
.client_information_flex_box {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: start;
    gap: 20px;
    margin-top: 30px;
}

.mdmebules_address p {
    color: #333;
    font-size: 18px;
    font-weight: 600;
    line-height: 35px;
}

/* second section */
.m-new-entry-list {
    border-radius: 26px;
    background: #fff;
    margin-top: 25px;
}
.contaner {
    padding: 30px;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    overflow: hidden;
}

.table_heading {
    height: 60px;
    flex-shrink: 0;
    border-radius: 8px;
    background: #f8f8f8;
}
.table_data td {
    padding: 27px 0 0 15px;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    line-height: 100%;
    width: 100%;
}
.table_data td input {
    border: 1px solid #333;
    outline: none;
    background: transparent;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    width: -webkit-fill-available;
}
.table_data td {
    padding: 27px 0 0 15px;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    line-height: 100%;
    width: 100%;
}
.table_data td.qty button {
    width: 30px;
    height: 35px;
    border-radius: 5px;
    background: #df3153;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    border: none;
    cursor: pointer;
}
.table_data td.qty input {
    width: calc(100% - 120px);
    text-align: center;
}
.table_data td.qty button:first-child {
    margin-right: 5px;
}
.table_data td.qty button:last-child {
    margin-left: 5px;
    background-color: #0f8677;
}
.table_data td:last-child input {
    width: calc(100% - 75px);
}
.table_data td:last-child {
    width: fit-content;
    justify-content: space-between;
    display: flex;
}
.table_data td:last-child button {
    width: 30px;
    height: 35px;
    border-radius: 5px;
    background: #df3153;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    border: none;
    cursor: pointer;
}

.button {
    margin-top: 30px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
}
.button button {
    width: 157px;
    height: 48px;
    border-radius: 8px;
    background: #0f8677;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    border: none;
    cursor: pointer;
}
.note {
    margin-top: 30px;
}
.note .row {
    display: flex;
    gap: 20px;
}
.note .col {
    width: 50%;
}
.note label {
    display: block;
    color: #333;
    font-size: 16px;
    font-weight: 700;
    line-height: 100%;
    margin-bottom: 0.5rem;
}
.note select {
    border: 1px solid #333;
    outline: none;
    background: transparent;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    width: -webkit-fill-available;
    margin-bottom: 1rem;
}
.note input {
    border: 1px solid #333;
    outline: none;
    background: transparent;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    width: -webkit-fill-available;
    margin-bottom: 1rem;
}
.select-group {
    display: flex;
    gap: 20px;
}
.note textarea {
    border: 1px solid #333;
    outline: none;
    background: transparent;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    width: -webkit-fill-available;
    height: 245px;
}
.bottom {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 40px 0;
}
.submit_btn {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    width: 157px;
    height: 48px;
    border-radius: 8px;
    background: #df3153;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    border: none;
    cursor: pointer;
}
</style>
