import React, {useState} from 'react';
import {TextInput, TouchableOpacity, View} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import {styles} from '../styles/app';

export const InputGroup = (props: any) => {
  const [showKey, setShowKey] = useState(props.password);
  return (
    <View style={styles.inputContent}>
      <TextInput
        style={styles.inputLine}
        onChangeText={props.setText}
        placeholder={props.title}
        secureTextEntry={showKey}
      />
      <View style={styles.icon}>
        <TouchableOpacity
          onPress={() => {
            props.password ? setShowKey(!showKey) : false;
          }}>
          <Icon
            name={!showKey && props.password ? 'eye-off' : props.iconName}
            size={25}
            color="#000"
          />
        </TouchableOpacity>
      </View>
    </View>
  );
};
