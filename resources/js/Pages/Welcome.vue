<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">

      <header class="bg-white dark:bg-gray-800 shadow-md">
        <div class="container mx-auto px-4 py-4">
          <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
              <span class="text-emerald-600 dark:text-emerald-400">classificação</span>Fornecedores
            </h1>
          </div>
        </div>
      </header>
  
 
      <main class="container mx-auto px-4 py-6">

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Nova Solicitação de Demanda</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição da Demanda</label>
              <select v-model="selectedDemand" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                <option value="" disabled>Selecione uma demanda</option>
                <option v-for="demand in demands" :key="demand.id" :value="demand">{{ demand.description }}</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP do Local da Demanda</label>
              <input v-model="demandLocation" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="00000-000" />
            </div>
          </div>
          <div class="mt-4 flex justify-end">
            <button @click="generateSupplierList" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors">
              Gerar Lista de Fornecedores
            </button>
          </div>
        </div>
  

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Listas de Fornecedores Geradas</h2>
            <button @click="openSupplierModal" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
              </svg>
              Cadastrar Fornecedor
            </button>
          </div>
          
          <div v-if="generatedLists.length === 0" class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
              <line x1="3" x2="21" y1="9" y2="9"></line>
              <path d="M8 14h.01"></path>
              <path d="M12 14h.01"></path>
              <path d="M16 14h.01"></path>
              <path d="M8 18h.01"></path>
              <path d="M12 18h.01"></path>
              <path d="M16 18h.01"></path>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Nenhuma lista de fornecedores foi gerada ainda.</p>
          </div>
  
          <div v-else class="space-y-6">
            <div v-for="(list, index) in generatedLists" :key="index" class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
              <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <div>
                  <h3 class="font-medium text-gray-800 dark:text-white">{{ list.demand.description }}</h3>
                  <div class="flex flex-wrap gap-2 mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                      </svg>
                      CEP: {{ list.location }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 8v4l3 3"></path>
                        <circle cx="12" cy="12" r="10"></circle>
                      </svg>
                      {{ formatDate(list.createdAt) }}
                    </span>
                  </div>
                </div>
                <button @click="toggleListDetails(index)" class="mt-2 sm:mt-0 px-3 py-1 text-sm bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 rounded-md flex items-center transition-colors">
                  {{ list.showDetails ? 'Ocultar' : 'Detalhes' }}
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" :class="{ 'transform rotate-180': list.showDetails }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"></path>
                  </svg>
                </button>
              </div>
              
              <div v-if="list.showDetails" class="px-4 py-3">
                <div v-if="list.suppliers.length === 0" class="text-center py-4">
                  <p class="text-amber-600 dark:text-amber-400">Não há fornecedores habilitados para esta demanda.</p>
                </div>
                <div v-else>
                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                      <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                          <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posição</th>
                          <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fornecedor</th>
                          <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CEP</th>
                          <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Distância</th>
                          <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Critério</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="(supplier, supplierIndex) in list.suppliers" :key="supplierIndex" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                          <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ supplierIndex + 1 }}</td>
                          <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.name }}</td>
                          <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.cep }}</td>
                          <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ supplier.distance }} km</td>
                          <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <span v-if="supplier.tiebreaker" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                              Desempate (Round-Robin)
                            </span>
                            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                              Menor Distância
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Demandas Cadastradas</h2>
            <button @click="openDemandModal" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 5v14M5 12h14"></path>
              </svg>
              Cadastrar Demanda
            </button>
          </div>
          
          <div v-if="demands.length === 0" class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
              <path d="M15 2H9a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z"></path>
            </svg>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Nenhuma demanda cadastrada.</p>
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                  <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fornecedores</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="demand in demands" :key="demand.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ demand.id }}</td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ demand.description }}</td>
                  <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                    {{ demand.suppliers.length }} fornecedor(es)
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
  

        <div v-if="showSupplierModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cadastrar Novo Fornecedor</h3>
                <button @click="closeSupplierModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Razão Social</label>
                  <input v-model="newSupplier.name" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="Nome do Fornecedor" />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP</label>
                  <input v-model="newSupplier.cep" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="00000-000" />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Demanda</label>
                  <select v-model="selectedDemandForSupplier" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
                    <option v-for="demand in demands" :key="demand.id" :value="demand.id">{{ demand.description }}</option>
                  </select>
                </div>
              </div>
              
              <div class="mt-6 flex justify-end">
                <button @click="closeSupplierModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors mr-2">
                  Cancelar
                </button>
                <button @click="addSupplier" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors">
                  Cadastrar
                </button>
              </div>
            </div>
          </div>
        </div>
  

        <div v-if="showDemandModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
              <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Cadastrar Nova Demanda</h3>
                <button @click="closeDemandModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
              
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição da Demanda</label>
                  <input v-model="newDemand.description" type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white" placeholder="Descrição da demanda" />
                </div>
              </div>
              
              <div class="mt-6 flex justify-end">
                <button @click="closeDemandModal" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium rounded-md shadow-sm transition-colors mr-2">
                  Cancelar
                </button>
                <button @click="addDemand" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-md shadow-sm transition-colors">
                  Cadastrar
                </button>
              </div>
            </div>
          </div>
        </div>
      </main>
  
      <footer class="bg-white dark:bg-gray-800 shadow-inner py-4">
        <div class="container mx-auto px-4 text-center text-gray-500 dark:text-gray-400 text-sm">
          &copy; {{ new Date().getFullYear() }} classifique - Sistema de Gestão de Fornecedores
        </div>
      </footer>
    </div>
  </template>
  
<script>
  export default {
    data() {
      return {
        selectedDemand: "",
        demandLocation: "",
        generatedLists: [],
        demands: [
          {
            id: 1,
            description: "Serviços de Manutenção Predial",
            suppliers: [
              { name: "Construtora Alpha Ltda", cep: "01001-000", distance: 0 },
              { name: "Manutenção Express S.A.", cep: "04001-000", distance: 0 },
              { name: "Predial Serviços Gerais", cep: "05001-000", distance: 0 },
              { name: "Reforma & Cia", cep: "02001-000", distance: 0 }
            ]
          },
          {
            id: 2,
            description: "Fornecimento de Material de Escritório",
            suppliers: [
              { name: "Papelaria Central", cep: "01002-000", distance: 0 },
              { name: "Office Supply Ltda", cep: "03001-000", distance: 0 },
              { name: "Distribuidora de Materiais Omega", cep: "04002-000", distance: 0 }
            ]
          },
          {
            id: 3,
            description: "Serviços de Limpeza",
            suppliers: [
              { name: "Limpeza Total", cep: "02002-000", distance: 0 },
              { name: "Higiene & Cia", cep: "01003-000", distance: 0 }
            ]
          },
          {
            id: 4,
            description: "Fornecimento de Equipamentos de TI",
            suppliers: []
          }
        ],
        // New modal state variables
        showSupplierModal: false,
        showDemandModal: false,
        newSupplier: {
          name: "",
          cep: ""
        },
        newDemand: {
          description: "",
          suppliers: []
        },
        selectedDemandForSupplier: null
      };
    },
    methods: {
      generateSupplierList() {
        if (!this.selectedDemand || !this.demandLocation) {
          alert("Por favor, selecione uma demanda e informe o CEP do local.");
          return;
        }
  
        // Calculate distances (in a real app, this would use a CEP API)
        const suppliers = this.selectedDemand.suppliers.map(supplier => {
          // Simulate distance calculation based on CEP difference
          // In a real app, you would use a proper distance calculation API
          const distance = this.calculateMockDistance(supplier.cep, this.demandLocation);
          return {
            ...supplier,
            distance,
            tiebreaker: false
          };
        });
  
        // Sort by distance
        suppliers.sort((a, b) => a.distance - b.distance);
  
        // Apply round-robin for tied distances
        this.applyRoundRobin(suppliers);
  
        // Create new list
        const newList = {
          demand: this.selectedDemand,
          location: this.demandLocation,
          createdAt: new Date(),
          suppliers,
          showDetails: true
        };
  
        // Add to generated lists
        this.generatedLists.unshift(newList);
  
        // Reset form
        this.selectedDemand = "";
        this.demandLocation = "";
      },
  
      calculateMockDistance(supplierCep, demandCep) {
        // This is a mock function - in a real app, you would use a CEP API
        // For demo purposes, we'll generate a random distance between 0 and 50
        // But ensure that some suppliers have the same distance for demonstrating the round-robin
        
        // Extract numeric part of CEP for simple calculation
        const supplierNum = parseInt(supplierCep.replace(/\D/g, ''));
        const demandNum = parseInt(demandCep.replace(/\D/g, ''));
        
        // Create deterministic but seemingly random distance based on CEP difference
        let distance = Math.abs(supplierNum - demandNum) % 50;
        
        // Round to 1 decimal place
        return Math.round(distance * 10) / 10;
      },
  
      applyRoundRobin(suppliers) {
        // Group suppliers by distance
        const distanceGroups = {};
        suppliers.forEach(supplier => {
          if (!distanceGroups[supplier.distance]) {
            distanceGroups[supplier.distance] = [];
          }
          distanceGroups[supplier.distance].push(supplier);
        });
  
        // Apply round-robin for each group with more than one supplier
        Object.keys(distanceGroups).forEach(distance => {
          const group = distanceGroups[distance];
          if (group.length > 1) {
            // Mark all but the first as tiebreakers
            for (let i = 1; i < group.length; i++) {
              group[i].tiebreaker = true;
            }
            
            // For demonstration purposes, we'll shuffle the tied suppliers
            // In a real round-robin implementation, you'd need to track previous orders
            this.shuffleArray(group);
          }
        });
  
        // Rebuild the suppliers array with the correct order
        let index = 0;
        Object.keys(distanceGroups).sort((a, b) => a - b).forEach(distance => {
          distanceGroups[distance].forEach(supplier => {
            suppliers[index++] = supplier;
          });
        });
      },
  
      shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
      },
  
      toggleListDetails(index) {
        this.generatedLists[index].showDetails = !this.generatedLists[index].showDetails;
      },
  
      formatDate(date) {
        return new Intl.DateTimeFormat('pt-BR', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit'
        }).format(date);
      },
  
      // New methods for modals
      openSupplierModal() {
        this.showSupplierModal = true;
        this.newSupplier = { name: "", cep: "" };
        this.selectedDemandForSupplier = this.demands[0].id;
      },
  
      closeSupplierModal() {
        this.showSupplierModal = false;
      },
  
      openDemandModal() {
        this.showDemandModal = true;
        this.newDemand = { description: "", suppliers: [] };
      },
  
      closeDemandModal() {
        this.showDemandModal = false;
      },
  
      addSupplier() {
        if (!this.newSupplier.name || !this.newSupplier.cep) {
          alert("Por favor, preencha todos os campos.");
          return;
        }
  
        // Find the demand to add the supplier to
        const demandIndex = this.demands.findIndex(d => d.id === this.selectedDemandForSupplier);
        if (demandIndex !== -1) {
          this.demands[demandIndex].suppliers.push({
            name: this.newSupplier.name,
            cep: this.newSupplier.cep,
            distance: 0
          });
        }
  
        this.closeSupplierModal();
      },
  
      addDemand() {
        if (!this.newDemand.description) {
          alert("Por favor, informe a descrição da demanda.");
          return;
        }
  
        // Generate a new ID
        const newId = Math.max(...this.demands.map(d => d.id), 0) + 1;
        
        this.demands.push({
          id: newId,
          description: this.newDemand.description,
          suppliers: []
        });
  
        this.closeDemandModal();
      }
    }
  };
</script>
  
<style>
  /* Additional styles can be added here if needed */
</style>