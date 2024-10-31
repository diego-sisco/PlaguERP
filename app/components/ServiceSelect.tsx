import React, { useState } from 'react';
import { View, Text } from 'react-native';
import SelectDropdown from 'react-native-select-dropdown';

export const ServiceSelect = (props: any) => {
    const [selectedService, setSelectedService] = useState(null);

    const serviceItems = props.services.map((service: any) => ({
        value: service.id,
        label: `${service.name}`,
    }));

    return (
        <View>
            <Text>Selecciona un servicio:</Text>
            <SelectDropdown
                data={serviceItems}
                onSelect={(selectedItem, index) => {
                    setSelectedService(selectedItem);
                    props.onSelect && props.onSelect(selectedItem);
                }}
                buttonTextAfterSelection={(selectedItem, index) => {
                    return selectedItem.label;
                }}
                rowTextForSelection={(item, index) => item.label}
                buttonStyle={{ borderColor: '#ccc', borderWidth: 1, padding: 10 }}
                buttonTextStyle={{ color: '#000' }}
                dropdownStyle={{ marginTop: 2, marginLeft: -5 }}
                rowStyle={{ padding: 10, height: 40 }}
                rowTextStyle={{ color: '#000' }}
                defaultButtonText="Selecciona un servicio"
                renderCustomizedButtonChild={(selectedItem, index) => {
                    return <Text style={{ color: '#000' }}>{selectedService ? selectedService.label : 'Selecciona un servicio'}</Text>;
                }}
            />
        </View>
    );
};