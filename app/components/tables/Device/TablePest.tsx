import React, {useState, useEffect} from 'react';
import {Text, View, TouchableOpacity} from 'react-native';
import {styles} from '../../../styles/app';
import Icon from 'react-native-vector-icons/Ionicons';
import {PestType} from '../../../types/Pest';
import {AnswerType} from '../../../types/Answer';

type TablePest = {
  id: number;
  name: string;
  value: string | number | null | undefined;
};

interface TableProps {
  selectedPests: AnswerType[]; // Recibe el array de datos como prop
  pests: PestType[];
  setSelectedPests: (newPests: AnswerType[]) => void;
}

export const TablePests: React.FC<TableProps> = ({
  selectedPests,
  pests,
  setSelectedPests,
}) => {
  const [tablePest, setTablePest] = useState<TablePest[]>([]);

  useEffect(() => {
    let table_data: TablePest[] = [];
    selectedPests.forEach((item: AnswerType) => {
      let name = pests.find((pest: PestType) => pest.id == item.id)?.name;
      table_data.push({
        id: item.id,
        name: name ?? '',
        value: item.value,
      });
    });
    setTablePest(table_data);
  }, [selectedPests, pests]); // Agregar `selectedPests` y `pests` a las dependencias

  const handleDelete = (id: number) => {
    // Filtra la tabla de plagas
    const updatedTablePest = tablePest.filter(item => item.id != id);
    setTablePest(updatedTablePest);

    // Filtra selectedPests para eliminar la plaga
    const updatedSelectedPests: AnswerType[] = selectedPests.filter(pest => pest.id != id);

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
