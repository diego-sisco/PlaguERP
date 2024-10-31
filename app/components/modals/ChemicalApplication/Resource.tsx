import React, {useState, useEffect} from 'react';
import {View, Button, Modal, Text, TextInput} from 'react-native';
import DropDownPicker from 'react-native-dropdown-picker'; // Importar la librería
import {styles} from '../../../styles/app';
import {ServiceType} from '../../../types/Service';
import {ProductType} from '../../../types/Product';
import {ApplicationMethodType} from '../../../types/ApplicationMethod';
import {InputResource, Resource} from '../../../types/Resource'


interface ModalProps {
  services: ServiceType[];
  products: ProductType[];
  appMethods: ApplicationMethodType[];
  selectedResources: Resource[];
  modalVisible: boolean;
  setModalVisible: (visible: boolean) => void;
  setResource: (newResources: Resource[]) => void;
}

export const ModalResource: React.FC<ModalProps> = ({
  services,
  products,
  appMethods,
  selectedResources,
  modalVisible,
  setModalVisible,
  setResource,
}) => {
  const [selectedService, setSelectedService] = useState<number | null>(null); // Estado para el servicio seleccionado
  const [selectedProduct, setSelectedProduct] = useState<number | null>(null); // Estado para la plaga seleccionada
  const [selectedAppMethod, setSelectedAppMethod] = useState<number | null>(
    null,
  ); // Estado para el método de aplicación seleccionada

  const [serviceOpen, setServiceOpen] = useState(false); // Controlar el dropdown de servicios
  const [pestOpen, setPestOpen] = useState(false); // Controlar el dropdown de plagas
  const [appMethodOpen, setAppMethodOpen] = useState(false); // Controlar el dropdown de plagas

  const [productQuantity, setProductQuantity] = useState(''); // Estado para la cantidad de plagas encontradas

  const storeResource = () => {
    if (selectedService && selectedProduct && selectedAppMethod) {
      const newSelectedResources = [...selectedResources]; // Copia del estado actual
      const foundServiceIndex = newSelectedResources.findIndex(
        (item: Resource) => item.service_id == selectedService,
      );

      if (foundServiceIndex != -1) {
        // Buscamos si ya existe un producto con el mismo ID en el servicio
        const foundProductIndex = newSelectedResources[
          foundServiceIndex
        ].array_ids.findIndex(
          (item: InputResource) =>
            item.product_id == selectedProduct &&
            item.appMethod_id == selectedAppMethod,
        );

        if (foundProductIndex != -1) {
          // Si ya existe, actualizamos la cantidad
          newSelectedResources[foundServiceIndex].array_ids[
            foundProductIndex
          ].value = parseInt(productQuantity);
        } else {
          // Si no existe, agregamos el producto y el método de aplicación
          newSelectedResources[foundServiceIndex].array_ids.push({
            product_id: selectedProduct,
            appMethod_id: selectedAppMethod,
            value: parseInt(productQuantity),
          });
        }
      } else {
        // Si no existe el servicio, lo agregamos con el nuevo producto y método de aplicación
        newSelectedResources.push({
          service_id: selectedService,
          array_ids: [
            {
              product_id: selectedProduct,
              appMethod_id: selectedAppMethod,
              value: parseInt(productQuantity),
            },
          ],
        });
      }

      // Actualizamos el estado de recursos seleccionados
      setResource(newSelectedResources);
      resetModal(); // Reiniciamos los campos del modal
    }
  };

  const resetModal = () => {
    setSelectedService(null);
    setSelectedProduct(null);
    setProductQuantity('');
    setModalVisible(false); // Cierra el modal
  };

  return (
    <Modal
      animationType="fade"
      transparent={true}
      visible={modalVisible}
      onRequestClose={resetModal}>
      <View style={styles.modalBackground}>
        <View style={styles.modalContainer}>
          {/* Título */}
          <View style={styles.section}>
            <Text style={styles.title}>Seleccione servicio y plaga</Text>
          </View>

          {/* Dropdown para seleccionar servicio */}
          <View style={{zIndex: 3000}}>
            <DropDownPicker
              open={serviceOpen}
              value={selectedService}
              items={services.map(service => ({
                label: service.name,
                value: service.id,
              }))}
              setOpen={setServiceOpen}
              setValue={setSelectedService}
              placeholder="Seleccione un servicio"
              containerStyle={styles.dropdown}
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
            />
          </View>

          {/* Dropdown para seleccionar producto */}
          <View style={{zIndex: 2000}}>
            <DropDownPicker
              open={pestOpen}
              value={selectedProduct}
              items={products.map(product => ({
                label: product.name,
                value: product.id,
              }))}
              setOpen={setPestOpen}
              setValue={setSelectedProduct}
              placeholder="Seleccione un producto"
              containerStyle={styles.dropdown}
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
            />
          </View>

          {/* Dropdown para seleccionar método de aplicación */}
          <View style={{zIndex: 1000}}>
            <DropDownPicker
              open={appMethodOpen}
              value={selectedAppMethod}
              items={appMethods.map(appMethod => ({
                label: appMethod.name,
                value: appMethod.id,
              }))}
              setOpen={setAppMethodOpen}
              setValue={setSelectedAppMethod}
              placeholder="Seleccione un método de aplicación"
              containerStyle={styles.dropdown}
              dropDownContainerStyle={{
                maxHeight: '100%',
              }}
            />
          </View>

          {/* Input para cantidad de plagas encontradas */}
          <View style={styles.section}>
            <Text style={styles.label}>Cantidad de producto utilizado (ml/uds):</Text>
            <TextInput
              style={styles.input}
              keyboardType="numeric"
              value={productQuantity}
              onChangeText={setProductQuantity}
              placeholder="Ingrese la cantidad"
            />
          </View>

          {/* Botón para Agregar */}
          <View style={styles.buttons}>
            {/* Botón para agregar */}
            <View style={styles.modalButton}>
              <Button title="Agregar" color="#0d6efd" onPress={storeResource} />
            </View>
            {/* Botón para cancelar */}
            <View style={styles.modalButton}>
              <Button title="Cancelar" color="#dc3545" onPress={resetModal} />
            </View>
          </View>
        </View>
      </View>
    </Modal>
  );
};
