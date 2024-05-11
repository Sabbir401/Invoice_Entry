<script setup>
  import { ref, watch, onMounted } from "vue";

  const qty = ref(0);
  const methods = ref();
  const error = ref();

  const unit_price = ref(0);
  const total_price = ref(0);

  const hours = ref([]);
    for (let i = 0; i < 24; i++) {
    hours.value.push(`${i < 10 ? '0' + i : i}.00h`);
  }

  watch([qty, unit_price], () => {
    total_price.value = qty.value * unit_price.value;
  });


  const transaction = ref([{
    qty: qty, 
    unit_price: unit_price, 
    total_price: total_price, 
    visible: true 
  }]);

const addQty = () => {
  qty.value ++
};

const subQty = () => {
  qty.value --
};

const addTransactionLine = () => {
  transaction.value.push({ qty: 0, unit_price: 0, total_price: 0, visible: true });
};

const hideLine = () => {
  console.log('hide');
};

const getData = async () => {
  try {
    const response = await axios.get("/api/method");
    methods.value = response.data;
  } catch (err) {
    error.value = err.message || "Error fetching data";
  } finally {
  }
};

const submit = async () => {
  try {
    const response = await axios.post("/api/transaction", transaction.value);
      resetForm();
      alert("Successfully Inserted");
  } catch (err) {
    console.error("Error submitting form:", err);
  }
};

onMounted(() => getData());

</script>

<template>
    <div class="row con">
        <div class="col-lg-2 sidebar">
            <button class="btn btn-success m-5 px-5">+ Ajouter</button>
        </div>
        <div class="col-lg-10 main">
            <form @submit="submit">
              <h3 class="mt-3">Ajouter une nouvelle entrée</h3>
              <div class="row">
                <div class="form-group col-lg-4">
                  <label>Date de Commande</label>
                  <input
                    type="date"
                    class="form-control"
                    id="exampleInputEmail1"
                    placeholder=""
                  />
                </div>
                <div class="form-group col-lg-4">
                  <label>Date de Livraison</label>
                  <input
                    type="date"
                    class="form-control"
                    id="exampleInputEmail1"
                    placeholder=""
                  />
                </div>
                <div class="form-group col-lg-4">
                  <label>Jour</label>
                  <input
                    type="text"
                    class="form-control"
                    id="exampleInputEmail1"
                    placeholder="Jour"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="card mb-3">
                    <div class="card-body">
                        <h3>Bd Meubles</h3>
                        <p>86 Boulevard Felix Faure, 93300 Aubervilliers, France</p>
                        <p>07 58 17 06 52</p>
                        <p>07 53 33 51 44</p>
                        <p>06 27 92 09 99</p>
                    </div>
                  </div>
                </div>

                <div class="col-lg-8">
                  <div class="card mb-3">
                    <div class="card-body">
                      <div class="d-flex">
                        <div class="mr-auto p-2">
                          <div class="row">
                            <h4 class="card-title m-2">Client</h4>
                          </div>
                        </div>
                      </div>
        
                        <div class="row">
                          <div class="form-group col-lg-6">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputUsername1"
                              placeholder="Prenom"
                            />
                          </div>
                          <div class="form-group col-lg-6">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputEmail1"
                              placeholder="Nom"
                            />
                          </div>
                        </div>


                        <div class="row">
                          <div class="form-group col-lg-6">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputUsername1"
                              placeholder="Telephone"
                            />
                          </div>
                          <div class="form-group col-lg-6">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputEmail1"
                              placeholder="Address"
                            />
                          </div>
                        </div>
                        <div class="row">
                          <div class="form-group col-lg-3">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputUsername1"
                              placeholder="Code Postal"
                            />
                          </div>
                          <div class="form-group col-lg-3">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputEmail1"
                              placeholder="Ville"
                            />
                          </div>
                          <div class="form-group col-lg-3">
                            <input
                              type="text"
                              class="form-control"
                              id="exampleInputEmail1"
                              placeholder="Etage"
                            />
                          </div>
                        </div>

                    </div>
                  </div>


                </div>
              </div>

              <div class="card mb-3 rounded-top">
                <div class="card-body">
                  <div class="row mb-5">
                    <div class="col-lg-2">Référence</div>
                    <div class="col-lg-2">Designation</div>
                    <div class="col-lg-3">Qté</div>
                    <div class="col-lg-2">Prix unitaire</div>
                    <div class="col-lg-3">Prix Total HT</div>
                  </div>


                  <div class="row" >
                  <div class="form-group col-lg-2">
                    <input type="text" class="form-control" placeholder="Référence" />
                  </div>
                  <div class="form-group col-lg-2">
                    <input type="text" class="form-control" placeholder="Designation" />
                  </div>
                  <div class="form-group col-lg-3">
                    <div class="row d-flex">
                      <div>
                        <button class="btn btn-danger" @click="subQty">-</button>
                      </div>
                      <div>
                        <input type="text" class="form-control" v-model="qty" />
                      </div>
                      <div>
                        <button class="btn btn-success" @click="addQty">+</button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group col-lg-2">
                    <input type="text" class="form-control" placeholder="0" v-model="unit_price" />
                  </div>
                  <div class="form-group col-lg-3">
                    <div class="row d-flex">
                      <div>
                        <input type="text" class="form-control" placeholder="0" v-model="total_price" />
                      </div>
                      <div>
                        <button class="btn btn-danger ml-2" @click="hideLine">X</button>
                      </div>
                    </div>
                  </div>
                </div>

                  <div class="row">
                    <div class="col-lg-9"></div>
                    <div class="col-lg-3">
                      <div class="row d-flex">
                        <div><p>Total HT</p></div>
                        <div><input type="text" class="form-control" id="exampleInputEmail1" placeholder="0" v-model="total_price"/></div>
                      </div>
                    </div>
                  </div>
                  <div class="row justify-content-center mb-3">
                  <div class="col-lg-2">
                    <button class="btn btn-success mb-4" @click="addTransactionLine">Ajouter</button>
                  </div>
                </div>

                <div class="row">
                  <div class ="col-lg-6">
                    <div class="col-lg-12">
                      <label>Méthode de paiement</label>
                      <select name="" id="" class="form-control mb-3" >
                        <option v-for="method in methods" :key="method.id" :value="method.id">{{method.Name}}</option>
                      </select>
                    </div>
                    <div class="col-lg-12">
                      <label>Payé</label>
                      <input type="text" class="form-control mb-3" id="exampleInputEmail1" placeholder="0.00"/>
                    </div>
                    <div class="col-lg-12">
                      <label>Reste à payer</label>
                      <input type="text" class="form-control mb-3" id="exampleInputEmail1" placeholder="0.00"/>
                    </div>
                    <div class="row m-1">
                      <div class="col-lg-6">
                        <label>L'heure de livraison</label>
                        <select name="" id="" class="form-control mb-3">
                          <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                        </select>
                      </div>
                      <div class="col-lg-6">
                        <label></label>
                        <select name="" id="" class="form-control mt-2 mb-3">
                          <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                        </select>
                      </div>
                    </div>

                  </div>
                  <div class ="col-lg-6">
                    <label>Note</label>
                    <textarea  class="form-control" rows="12"></textarea>
                  </div>
                </div>
                </div>
              </div>


              <div class="row justify-content-end mb-3">
                <div class="col-lg-2">
                  <button type="submit" class="btn btn-danger">Submit</button>
                </div>
              </div>
            </form>
          </div>

    </div>
</template>

<style scoped>
    .main{
        background-color: #EAE6E6;
    }

</style>
