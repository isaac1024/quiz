'use client'

import styles from './TextInput.module.css'
import { ChangeEvent } from 'react'

export type TextInputProps = {
  name: string
  value: string
  onChange: (event: ChangeEvent<HTMLInputElement>) => void
  type?: 'text' | 'password'
}

export function TextInput({
  name,
  value,
  onChange,
  type = 'text',
}: TextInputProps) {
  return (
    <input
      className={styles.input}
      id={name}
      name={name}
      type={type}
      value={value}
      onChange={onChange}
    />
  )
}
