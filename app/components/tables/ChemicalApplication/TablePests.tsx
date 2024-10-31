import React, { useState, useEffect } from 'react';
import { Text, View, TouchableOpacity } from 'react-native';
import { styles } from '../../../styles/app';
import Icon from 'react-native-vector-icons/Ionicons';
import { PestType } from '../../../types/Pest';
import {ServicePest} from '../../../types/ServicePest';
import { AnswerType } from '../../../types/Answer';

type TablePest = {
  id: number;
  name: string;
  value: string | number | null | undefined;
};

interface TableProps {
  selectedPests: ServicePest[]; // Recibe el array de datos como prop
  pests: PestType[];
  serviceId: number;
  setSelectedPests: (newPests: ServicePest[]) => void;
}

export const TablePests: React.FC<TableProps> = ({ selectedPests, pests, serviceId, setSelectedPests }) => {
  const [tablePest, setTablePest] = useState<TablePest[]>([]);

  useEffect(() => {
    const fetchedData = selectedPests.filter((d: ServicePest) => d.key === serviceId);
    let table_data: TablePest[] = [];
    fetchedData.forEach((item: ServicePest) => {
      item.values.forEach((elem: AnswerType) => {
        let name = pests.find((pest: PestType) => pest.id === elem.id)?.name;
        table_data.push({
          id: elem.id,
          name: name ?? '',
          value: elem.value,
        });
      });
    });
    setTablePest(table_data);
  }, [selectedPests, pests, serviceId]); // Agregar `selectedPests` y `pests` a las dependencias

  const handleDelete = (id: number) => {
    // Filtra la tabla de plagas
    const updatedTablePest = tablePest.filter(item => item.id !== id);
    setTablePest(updatedTablePest);

    // Filtra selectedPests para eliminar la plaga
    const updatedSelectedPests = selectedPests.map(pest => ({
      ...pest,
      array_ids: pest.values.filter(elem => elem.id !== id),
    })).filter(pest => pest.values.length > 0); // Eliminar la plaga si no hay más elementos en array_ids

    setSelectedPests(updatedSelectedPests); // Actualiza selectedPests
  };

  return (
    <View style={styles.table}>
      <View style={styles.row}>
        <Text style={styles.headerCell}>Plaga</Text>
        <Text style={styles.headerCell}>Cantidad</Text>
        <Text style={styles.headerCell}>Acciones</Text>
      </View>

      {/* Renderiza cada fila del array de datos */}
      {tablePest.map(item => {
        return (
          <View key={item.id} style={styles.row}>
            <Text style={styles.cell}>{item.name}</Text>
            <Text style={styles.cell}>{item.value}</Text>
            <Text style={styles.cell}>
              <TouchableOpacity onPress={() => handleDelete(item.id)}>
                <Icon name="trash-outline" size={24} color="#dc3545" />
              </TouchableOpacity>
            </Text>
          </View>
        );
      })}
    </View>
  );
};

