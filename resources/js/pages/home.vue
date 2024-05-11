<script setup>
import { ref, onMounted, computed } from "vue";
import router from "../router";
const error = ref();
const orders = ref();
const showNotification = ref(false);

const getData = async () => {
    try {
        const response = await axios.get("/api/entry");
        orders.value = response.data.orders;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    }
};

const deleteEntry = async (id) => {
    const confirmed = window.confirm(
        "Are you sure you want to delete this entry?"
    );
    if (confirmed) {
        const response = await axios.delete(`/api/delete-entry/${id}`);
        if (response.data.message) {
            showNotification = true;
            setTimeout(() => {
                showNotification.value = false;
            }, 3000);
            getData();
        }
    } else {
        getData();
    }
};

const getTotalHT = (order) => {
    if (order.items.length > 0) {
        return order.items[order.items.length - 1].totalHT;
    }
    return "N/A"; // Return 'N/A' if items array is empty
};

const totalPages = computed(() => Math.ceil(orders.value.length / perPage));

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return orders.value.slice(start, end);
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

onMounted(() => getData());
</script>

<template>
    
    <div class="sidebar">
        <RouterLink to="/entry" class="m-sidebar_new_entry_btn"
            >+ Ajouter</RouterLink
        >
        <RouterLink to="/" class="m-sidebar_link"><p>Entrées</p></RouterLink>
    </div>
    <div class="content">
        <div class="page">
            <div class="m-header">
                <h1 class="m-header_title">Entrées</h1>
                <div class="m-header_search">
                    <input placeholder="Recherche" type="text" /><img
                        src="../images/search-alt-svgrepo-com-1.svg"
                        alt=""
                    />
                </div>
            </div>
            <div class="page_container">
                <div class="m-heading-container">
                    <p class="m-heading">Entrées</p>
                    <div class="m-filter-container">
                        <select>
                            <option disabled="" value="all">Filter</option>
                            <option value="text">Text</option>
                            <option value="date">Date</option></select
                        ><input type="search" placeholder="Rechercher" />
                    </div>
                </div>
                <table style="width: 100%">
                    <tr class="m-table-heading">
                        <th>ID</th>
                        <th>Client</th>
                        <th>Date de Commande</th>
                        <th>Date de Livraison</th>
                        <th>Total HT</th>
                        <th>Action</th>
                    </tr>
                    <tr
                        class="m-table-single-td"
                        v-for="order in orders"
                        :key="order.id"
                    >
                        <td class="m-table-flex-box">
                            {{ order.id }} ({{ order.delivery_number }})
                        </td>
                        <td>{{ order.first_name }}</td>
                        <td>{{ order.order_date }}</td>
                        <td>{{ order.delivery_date }}</td>
                        <td>€{{ getTotalHT(order) }}</td>
                        <td>
                            <div class="m-table-actions">
                                <img
                                    width="20"
                                    height="20"
                                    src="../images/printer.svg"
                                    alt=""
                                /><img
                                    width="20"
                                    height="20"
                                    src="../images/edit.svg"
                                    alt=""
                                    @click="router.push(`/entry/${order.id}`)"
                                /><img
                                    width="20"
                                    height="20"
                                    src="../images/trash-2.svg"
                                    alt=""
                                    @click="deleteEntry(order.id)"
                                />
                            </div>
                        </td>
                    </tr>
                </table>
                <div class="m-pagination">
                    <p class="m-pagination_text">
                        Affichage de 1 à 10 sur un total de 2949 entrées
                    </p>
                    <div class="m-pagination_btn-container">
                        <button
                            class="m-pagination_btn m-pagination_btn--active"
                        >
                            1</button
                        ><button class="m-pagination_btn">2</button
                        ><button class="m-pagination_btn" @click="nextPage">
                            >
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.sidebar {
    width: 328px;
    height: 100vh;
    position: fixed;
    border-right: 0.5px solid #c0bbbb;
    background-color: #fff;
}

.sidebar .m-sidebar_link {
    width: 300px;
    height: 50px;
    border-radius: 12px;
}

.sidebar .router-link-active:not(.m-sidebar__new_entry_btn) {
    border-radius: 0 12px 12px 0;
    background-color: #ffced8;
    color: #df3153;
    border-left: 5px solid #df3153;
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
.m-sidebar_new_entry_btn {
    margin-top: 100px;
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

.content {
    margin-left: 328px;
    background-color: #eae6e6;
}
.page {
    padding: 30px;
}
.m-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}
.m-header_title {
    color: #000;
    font-size: 30px;
    font-weight: 700;
    line-height: 100%;
}
.m-header_search {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    width: fit-content;
    position: relative;
}
.m-header_search input {
    width: 437px;
    height: 48px;
    border-radius: 12px;
    background: #fff;
    border: none;
    padding: 0 14px;
    color: #777;
    font-size: 18px;
    font-weight: 400;
    line-height: 100%;
}
.m-header_search img {
    position: absolute;
    right: 0;
    width: 26px;
    height: 26px;
    margin-right: 15px;
}
.page_container {
    margin-top: 30px;
    border-radius: 26px;
    background: #fff;
    padding: 20px;
}
.m-heading-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.m-heading {
    color: #333;
    font-size: 24px;
    font-weight: 700;
    line-height: 100%;
}
.m-filter-container {
    display: flex;
    justify-content: flex-end;
    align-items: center;
}
.m-filter-container > select {
    width: 200px;
    height: 40px;
    border-radius: 20px;
    border: 1px solid #333;
    background: #fff;
    padding: 0 10px;
    margin-right: 10px;
    font-size: 14px;
    color: #333;
    outline: none;
}
.m-filter-container > input {
    width: 200px;
    height: 40px;
    border-radius: 20px;
    border: 1px solid #333;
    background: #fff;
    padding: 0 10px;
    font-size: 14px;
    color: #333;
    outline: none;
}
.m-table-heading {
    height: 50px;
    background: #f8f8f8;
}
.m-table-heading th {
    padding-left: 10px;
    color: #333;
    text-align: start;
    font-size: 18px;
    font-weight: 700;
    line-height: 100%;
}
.m-table-single-td td {
    padding: 17px 0 17px 10px;
    color: #333;
    font-size: 16px;
    font-weight: 600;
    line-height: 100%;
}
.m-table-actions {
    height: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
}
.m-table-actions img {
    cursor: pointer;
}
.m-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
}
.m-pagination_text {
    font-size: 14px;
    color: #333;
}
.m-pagination_btn-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
.m-pagination_btn-container > button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #333;
    background: #fff;
    margin: 0 5px;
    font-size: 14px;
    color: #333;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
}

.notification {
    background-color: #4caf50; /* Green */
    color: white;
    text-align: center;
    padding: 16px;
    position: fixed;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
}
</style>
