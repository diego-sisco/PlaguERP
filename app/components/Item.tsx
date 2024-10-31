import React from 'react';
import {View, Text} from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';
import {StyleSheet} from 'react-native';

export const Item = (props: any) => {
  return (
    <View style={styles.item}>
      <Icon name={props.iconName} size={props.iconSize} color={props.color} />
      <View style={styles.text}>
        <Text style={{color: props.color}}>{props.name}</Text>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  item: {
    width: '100%',
    display: 'flex',
    flexDirection: 'row',
    alignitems: 'center',
    margin: 5,
  },

  text: {
    display: 'flex',
    justifyContent: 'center',
    margin: 5,
  }
});
