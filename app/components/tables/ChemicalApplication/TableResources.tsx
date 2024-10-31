import React, {useState, useEffect} from 'react';
import {Text, View, TouchableOpacity} from 'react-native';
import {styles} from '../../../styles/app';
import Icon from 'react-native-vector-icons/Ionicons';
import {ProductType} from '../../../types/Product';
import {ApplicationMethodType} from '../../../types/ApplicationMethod';
import {InputResource, Resource} from '../../../types/Resource';

type TableResource = {
  productId: number;
  appMethodId: number;
  productName: string;
  appMethodName: string;
  value: number | null;
};

interface TableProps {
  selectedResources: Resource[]; // Recibe el array de recursos seleccionados
  products: ProductType[]; // Lista de productos
  appMethods: ApplicationMethodType[]; // Lista de métodos de aplicación
  serviceId: number; // ID del servicio
  setSelectedResources: (newResources: Resource[]) => void;
}

export const TableResources: React.FC<TableProps> = ({
  selectedResources,
  products,
  appMethods,
  serviceId,
  setSelectedResources,
}) => {
  const [tableResource, setTableResource] = useState<TableResource[]>([]);

  useEffect(() => {
    const fetchedData = selectedResources.filter(
      (d: Resource) => d.service_id == serviceId,
    );
    let table_data: TableResource[] = [];

    fetchedData.forEach((item: Resource) => {
      item.array_ids.forEach((elem: InputResource) => {
        let productName = products.find(
          (product: ProductType) => product.id == elem.product_id,
        )?.name;
        let appMethodName = appMethods.find(
          (method: ApplicationMethodType) => method.id == elem.appMethod_id,
        )?.name;

        table_data.push({
          productId: elem.product_id,
          appMethodId: elem.appMethod_id,
          productName: productName ?? '',
          appMethodName: appMethodName ?? '',
          value: elem.value,
        });
      });
    });

    setTableResource(table_data);
  }, [selectedResources, products, appMethods, serviceId]);

  const handleDelete = (productId: number, appMethodId: number) => {
    // Filtra la tabla de recursos
    const updatedTableResource = tableResource.filter(
      item =>
        !(item.productId == productId && item.appMethodId == appMethodId),
    );
    setTableResource(updatedTableResource);

    // Filtra selectedResources para eliminar el producto y método de aplicación
    const updatedSelectedResources = selectedResources
      .map(resource => ({
        ...resource,
        array_ids: resource.array_ids.filter(
          elem =>
            !(
              elem.product_id == productId && elem.appMethod_id == appMethodId
            ),
        ),
      }))
      .filter(resource => resource.array_ids.length > 0); // Eliminar el recurso si no hay más elementos en array_ids

    setSelectedResources(updatedSelectedResources); // Actualiza selectedResources
  };

  return (
    <View style={styles.table}>
      <View style={styles.row}>
        <Text style={styles.headerCell}>Producto</Text>
        <Text style={styles.headerCell}>Método de aplicación</Text>
        <Text style={styles.headerCell}>Cantidad (ml/uds)</Text>
        <Text style={styles.headerCell}>Acciones</Text>
      </View>

      {/* Renderiza cada fila del array de recursos */}
      {tableResource.map(item => (
        <View key={`${item.productId}-${item.appMethodId}`} style={styles.row}>
          <Text style={styles.cell}>{item.productName}</Text>
          <Text style={styles.cell}>{item.appMethodName}</Text>
          <Text style={styles.cell}>{item.value}</Text>
          <Text style={styles.cell}>
            <TouchableOpacity
              onPress={() => handleDelete(item.productId, item.appMethodId)}>
              <Icon name="trash-outline" size={24} color="#dc3545" />
            </TouchableOpacity>
          </Text>
        </View>
      ))}
    </View>
  );
};
